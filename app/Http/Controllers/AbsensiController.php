<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pertemuan;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * GET /absensi/{id_pertemuan}/data
     * JSON endpoint untuk modal absensi (AJAX)
     */
    public function getData($id_pertemuan)
    {
        $pertemuan = Pertemuan::findOrFail($id_pertemuan);

        $absensi = Absensi::with('mahasiswa')
                          ->where('id_pertemuan', $id_pertemuan)
                          ->get();

        if ($absensi->isEmpty()) {
            // Ambil mahasiswa dari tabel krs berdasarkan id_kelas
            $mahasiswaList = DB::table('krs')
                ->where('krs.id_kelas', $pertemuan->id_kelas)
                ->join('absenmahasiswa.mahasiswa', 'krs.id_mahasiswa', '=', 'absenmahasiswa.mahasiswa.id_mahasiswa')
                ->select(
                    'absenmahasiswa.mahasiswa.id_mahasiswa',
                    'absenmahasiswa.mahasiswa.nim',
                    'absenmahasiswa.mahasiswa.nama_mahasiswa'
                )
                ->get();

            if ($mahasiswaList->isEmpty()) {
                return response()->json([]);
            }

            // Buat record absensi (ID urut ABS0001, ABS0002, dst)
            foreach ($mahasiswaList as $mhs) {
                $exists = Absensi::where('id_pertemuan', $id_pertemuan)
                                 ->where('id_mahasiswa', $mhs->id_mahasiswa)
                                 ->exists();
                if (!$exists) {
                    $lastAbs = Absensi::orderByRaw("id_absensi DESC")->value('id_absensi');
                    $lastNum = $lastAbs ? (int) substr($lastAbs, 3) : 0;
                    $newId   = 'ABS' . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

                    Absensi::create([
                        'id_absensi'   => $newId,
                        'id_pertemuan' => $id_pertemuan,
                        'id_mahasiswa' => $mhs->id_mahasiswa,
                        'status'       => 'hadir',
                    ]);
                }
            }

            $absensi = Absensi::with('mahasiswa')
                              ->where('id_pertemuan', $id_pertemuan)
                              ->get();
        }

        $result = $absensi->map(function ($a) {
            return [
                'id_absensi'     => $a->id_absensi,
                'id_mahasiswa'   => $a->id_mahasiswa,
                'nim'            => $a->mahasiswa->nim ?? null,
                'nama_mahasiswa' => $a->mahasiswa->nama_mahasiswa ?? '(tidak ditemukan)',
                'status'         => $a->status ?? 'hadir',
            ];
        });

        return response()->json($result);
    }

    /**
     * POST /absensi/update-status (JSON dari fetch modal)
     */
    public function updateStatus(Request $request)
    {
        try {
            Pertemuan::findOrFail($request->id_pertemuan);
            foreach ($request->status as $id_absensi => $status) {
                Absensi::where('id_absensi', $id_absensi)->update(['status' => $status]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}