<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Pertemuan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stats cards ──────────────────────────────────────────
        $totalMahasiswa  = DB::table('absenmahasiswa.mahasiswa')->count();
        $totalMataKuliah = DB::table('mata_kuliah')->count();
        $totalKelas      = DB::table('kelas')->count();
        $pertemuanAktif  = DB::table('pertemuan')
                             ->whereDate('tanggal', today())
                             ->count();

        // ── Pertemuan hari ini (detail) ───────────────────────────
        $pertemuanHariIni = DB::table('pertemuan')
            ->whereDate('tanggal', today())
            ->join('kelas', 'pertemuan.id_kelas', '=', 'kelas.id_kelas')
            ->join('mata_kuliah', 'kelas.id_mk', '=', 'mata_kuliah.id_mk')
            ->leftJoin('dosen', 'kelas.id_dosen', '=', 'dosen.id_dosen')
            ->select(
                'pertemuan.id_pertemuan',
                'pertemuan.pertemuan_ke',
                'pertemuan.jam_mulai',
                'pertemuan.jam_selesai',
                'kelas.id_kelas',
                'mata_kuliah.nama_mk',
                'dosen.nama_dosen'
            )
            ->orderBy('pertemuan.jam_mulai')
            ->get();

        // ── Libur Nasional Indonesia 2026 ─────────────────────────
        $liburNasional = collect([
            ['tanggal' => '2026-01-01', 'nama' => 'Tahun Baru 2026'],
            ['tanggal' => '2026-01-27', 'nama' => 'Isra Mi\'raj'],
            ['tanggal' => '2026-01-28', 'nama' => 'Tahun Baru Imlek'],
            ['tanggal' => '2026-03-20', 'nama' => 'Hari Raya Nyepi'],
            ['tanggal' => '2026-04-03', 'nama' => 'Wafat Isa Al Masih'],
            ['tanggal' => '2026-03-31', 'nama' => 'Idul Fitri 1447 H'],
            ['tanggal' => '2026-04-01', 'nama' => 'Idul Fitri 1447 H (Cuti)'],
            ['tanggal' => '2026-04-02', 'nama' => 'Idul Fitri 1447 H (Cuti)'],
            ['tanggal' => '2026-05-01', 'nama' => 'Hari Buruh Internasional'],
            ['tanggal' => '2026-05-14', 'nama' => 'Kenaikan Isa Al Masih'],
            ['tanggal' => '2026-05-25', 'nama' => 'Hari Raya Waisak'],
            ['tanggal' => '2026-06-01', 'nama' => 'Hari Lahir Pancasila'],
            ['tanggal' => '2026-06-07', 'nama' => 'Idul Adha 1447 H'],
            ['tanggal' => '2026-06-28', 'nama' => 'Tahun Baru Islam 1448 H'],
            ['tanggal' => '2026-08-17', 'nama' => 'HUT Kemerdekaan RI'],
            ['tanggal' => '2026-09-05', 'nama' => 'Maulid Nabi Muhammad SAW'],
            ['tanggal' => '2026-12-25', 'nama' => 'Hari Raya Natal'],
        ]);

        $today = Carbon::today();

        // Notifikasi: libur yang akan datang dalam 30 hari ke depan
        $notifikasi = $liburNasional->filter(function ($libur) use ($today) {
            $tgl = Carbon::parse($libur['tanggal']);
            return $tgl->between($today, $today->copy()->addDays(30));
        })->map(function ($libur) {
            return [
                'tanggal'   => $libur['tanggal'],
                'nama'      => $libur['nama'],
                'label_tgl' => Carbon::parse($libur['tanggal'])->translatedFormat('d F Y'),
                'hari_lagi' => Carbon::parse($libur['tanggal'])->diffInDays(today()),
            ];
        })->values();

        // Apakah hari ini hari libur?
        $liburHariIni = $liburNasional->firstWhere('tanggal', today()->toDateString());

        return view('dashboard', compact(
            'totalMahasiswa',
            'totalMataKuliah',
            'totalKelas',
            'pertemuanAktif',
            'pertemuanHariIni',
            'notifikasi',
            'liburHariIni'
        ));
    }

    /**
     * Static helper — dipanggil dari blade @json() untuk highlight kalender
     */
    public static function getLiburDates(): array
    {
        return [
            '2026-01-01','2026-01-27','2026-01-28','2026-03-20','2026-03-31',
            '2026-04-01','2026-04-02','2026-04-03','2026-05-01','2026-05-14',
            '2026-05-25','2026-06-01','2026-06-07','2026-06-28','2026-08-17',
            '2026-09-05','2026-12-25',
        ];
    }
}