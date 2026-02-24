<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapAbsensiController extends Controller
{
    /** GET /absensi-rekap */
    public function index(Request $request)
    {
        $mode     = $request->get('mode', 'kelas'); // 'kelas' atau 'mahasiswa'
        $id_kelas = $request->get('id_kelas');
        $id_mhs   = $request->get('id_mahasiswa');

        // Dropdown kelas
        $kelasList = DB::table('kelas')
            ->join('mata_kuliah', 'kelas.id_mk', '=', 'mata_kuliah.id_mk')
            ->leftJoin('dosen', 'kelas.id_dosen', '=', 'dosen.id_dosen')
            ->select('kelas.id_kelas', 'mata_kuliah.nama_mk', 'dosen.nama_dosen', 'kelas.tahun_ajaran')
            ->orderBy('mata_kuliah.nama_mk')
            ->get();

        // Dropdown mahasiswa
        $mahasiswaList = DB::table('absenmahasiswa.mahasiswa')
            ->orderBy('nama_mahasiswa')
            ->select('id_mahasiswa', 'nim', 'nama_mahasiswa')
            ->get();

        $rekap         = collect();
        $pertemuanList = collect();
        $selectedKelas = null;
        $selectedMhs   = null;

        // ── MODE: per kelas ─────────────────────────────────────
        if ($mode === 'kelas' && $id_kelas) {
            $selectedKelas = $kelasList->firstWhere('id_kelas', $id_kelas);

            // Daftar pertemuan di kelas ini
            $pertemuanList = DB::table('pertemuan')
                ->where('id_kelas', $id_kelas)
                ->orderBy('pertemuan_ke')
                ->get();

            // Mahasiswa di kelas ini (via KRS)
            $mahasiswaKelas = DB::table('krs')
                ->where('krs.id_kelas', $id_kelas)
                ->join('absenmahasiswa.mahasiswa', 'krs.id_mahasiswa', '=', 'absenmahasiswa.mahasiswa.id_mahasiswa')
                ->select('absenmahasiswa.mahasiswa.id_mahasiswa', 'absenmahasiswa.mahasiswa.nim', 'absenmahasiswa.mahasiswa.nama_mahasiswa')
                ->orderBy('absenmahasiswa.mahasiswa.nama_mahasiswa')
                ->get();

            // Semua absensi di kelas ini
            $semuaAbsensi = DB::table('absenmahasiswa.absensi')
                ->join('pertemuan', 'absenmahasiswa.absensi.id_pertemuan', '=', 'pertemuan.id_pertemuan')
                ->where('pertemuan.id_kelas', $id_kelas)
                ->select('absenmahasiswa.absensi.id_mahasiswa', 'absenmahasiswa.absensi.id_pertemuan', 'absenmahasiswa.absensi.status')
                ->get()
                ->groupBy('id_mahasiswa');

            $rekap = $mahasiswaKelas->map(function ($mhs) use ($pertemuanList, $semuaAbsensi) {
                $absensiMhs = $semuaAbsensi->get($mhs->id_mahasiswa, collect());
                $absensiByPertemuan = $absensiMhs->keyBy('id_pertemuan');

                $detail = $pertemuanList->map(function ($p) use ($absensiByPertemuan) {
                    return $absensiByPertemuan->get($p->id_pertemuan)?->status ?? '-';
                });

                $hadir = $detail->filter(fn($s) => $s === 'hadir')->count();
                $izin  = $detail->filter(fn($s) => $s === 'izin')->count();
                $sakit = $detail->filter(fn($s) => $s === 'sakit')->count();
                $alpha = $detail->filter(fn($s) => $s === 'alpha')->count();
                $total = $pertemuanList->count();

                return (object) [
                    'id_mahasiswa'   => $mhs->id_mahasiswa,
                    'nim'            => $mhs->nim,
                    'nama_mahasiswa' => $mhs->nama_mahasiswa,
                    'detail'         => $detail,
                    'hadir'          => $hadir,
                    'izin'           => $izin,
                    'sakit'          => $sakit,
                    'alpha'          => $alpha,
                    'persen_hadir'   => $total > 0 ? round($hadir / $total * 100) : 0,
                ];
            });
        }

        // ── MODE: per mahasiswa ──────────────────────────────────
        if ($mode === 'mahasiswa' && $id_mhs) {
            $selectedMhs = DB::table('absenmahasiswa.mahasiswa')
                ->where('id_mahasiswa', $id_mhs)->first();

            $rekap = DB::table('absenmahasiswa.absensi')
                ->where('absenmahasiswa.absensi.id_mahasiswa', $id_mhs)
                ->join('pertemuan', 'absenmahasiswa.absensi.id_pertemuan', '=', 'pertemuan.id_pertemuan')
                ->join('kelas', 'pertemuan.id_kelas', '=', 'kelas.id_kelas')
                ->join('mata_kuliah', 'kelas.id_mk', '=', 'mata_kuliah.id_mk')
                ->select(
                    'mata_kuliah.nama_mk',
                    'kelas.id_kelas',
                    'pertemuan.pertemuan_ke',
                    'pertemuan.tanggal',
                    'absenmahasiswa.absensi.status'
                )
                ->orderBy('mata_kuliah.nama_mk')
                ->orderBy('pertemuan.pertemuan_ke')
                ->get();
        }

        return view('absensi.rekap', compact(
            'mode', 'kelasList', 'mahasiswaList', 'rekap',
            'pertemuanList', 'selectedKelas', 'selectedMhs',
            'id_kelas', 'id_mhs'
        ));
    }

    /** GET /absensi-rekap/export — Export CSV */
    public function export(Request $request)
    {
        $mode     = $request->get('mode', 'kelas');
        $id_kelas = $request->get('id_kelas');
        $id_mhs   = $request->get('id_mahasiswa');

        $filename = 'rekap_absensi_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($mode, $id_kelas, $id_mhs) {
            $out = fopen('php://output', 'w');

            if ($mode === 'kelas' && $id_kelas) {
                $pertemuan = DB::table('pertemuan')
                    ->where('id_kelas', $id_kelas)
                    ->orderBy('pertemuan_ke')
                    ->get();

                $header = ['NIM', 'Nama Mahasiswa'];
                foreach ($pertemuan as $p) {
                    $header[] = 'Pertemuan ' . $p->pertemuan_ke;
                }
                $header = array_merge($header, ['Hadir', 'Izin', 'Sakit', 'Alpha', '% Hadir']);
                fputcsv($out, $header);

                $mahasiswaKelas = DB::table('krs')
                    ->where('krs.id_kelas', $id_kelas)
                    ->join('absenmahasiswa.mahasiswa', 'krs.id_mahasiswa', '=', 'absenmahasiswa.mahasiswa.id_mahasiswa')
                    ->select('absenmahasiswa.mahasiswa.id_mahasiswa', 'absenmahasiswa.mahasiswa.nim', 'absenmahasiswa.mahasiswa.nama_mahasiswa')
                    ->orderBy('absenmahasiswa.mahasiswa.nama_mahasiswa')
                    ->get();

                $semuaAbsensi = DB::table('absenmahasiswa.absensi')
                    ->join('pertemuan', 'absenmahasiswa.absensi.id_pertemuan', '=', 'pertemuan.id_pertemuan')
                    ->where('pertemuan.id_kelas', $id_kelas)
                    ->get()->groupBy('id_mahasiswa');

                foreach ($mahasiswaKelas as $mhs) {
                    $absByPertemuan = ($semuaAbsensi->get($mhs->id_mahasiswa, collect()))->keyBy('id_pertemuan');
                    $row = [$mhs->nim, $mhs->nama_mahasiswa];
                    $hadir = $izin = $sakit = $alpha = 0;
                    foreach ($pertemuan as $p) {
                        $status = $absByPertemuan->get($p->id_pertemuan)?->status ?? '-';
                        $row[] = $status;
                        if ($status === 'hadir') $hadir++;
                        elseif ($status === 'izin') $izin++;
                        elseif ($status === 'sakit') $sakit++;
                        elseif ($status === 'alpha') $alpha++;
                    }
                    $total = $pertemuan->count();
                    $row = array_merge($row, [$hadir, $izin, $sakit, $alpha, $total > 0 ? round($hadir/$total*100).'%' : '0%']);
                    fputcsv($out, $row);
                }

            } elseif ($mode === 'mahasiswa' && $id_mhs) {
                fputcsv($out, ['Mata Kuliah', 'ID Kelas', 'Pertemuan Ke', 'Tanggal', 'Status']);
                $rows = DB::table('absenmahasiswa.absensi')
                    ->where('absenmahasiswa.absensi.id_mahasiswa', $id_mhs)
                    ->join('pertemuan', 'absenmahasiswa.absensi.id_pertemuan', '=', 'pertemuan.id_pertemuan')
                    ->join('kelas', 'pertemuan.id_kelas', '=', 'kelas.id_kelas')
                    ->join('mata_kuliah', 'kelas.id_mk', '=', 'mata_kuliah.id_mk')
                    ->select('mata_kuliah.nama_mk', 'kelas.id_kelas', 'pertemuan.pertemuan_ke', 'pertemuan.tanggal', 'absenmahasiswa.absensi.status')
                    ->orderBy('mata_kuliah.nama_mk')->orderBy('pertemuan.pertemuan_ke')
                    ->get();
                foreach ($rows as $r) {
                    fputcsv($out, [$r->nama_mk, $r->id_kelas, $r->pertemuan_ke, $r->tanggal, $r->status]);
                }
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}