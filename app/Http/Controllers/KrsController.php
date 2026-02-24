<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class KrsController extends Controller
{
    /**
     * GET /krs
     * Halaman KRS mahasiswa: lihat kelas yang sudah diambil + browse kelas tersedia
     */
    public function index()
    {
        $mhs = session('mahasiswa');
        if (!$mhs) return redirect('/login');

        $id_mahasiswa = $mhs['id_mahasiswa'];

        // Kelas yang sudah diambil mahasiswa ini
        $krsAktif = DB::table('krs')
            ->where('krs.id_mahasiswa', $id_mahasiswa)
            ->join('kelas', 'krs.id_kelas', '=', 'kelas.id_kelas')
            ->join('mata_kuliah', 'kelas.id_mk', '=', 'mata_kuliah.id_mk')
            ->leftJoin('dosen', 'kelas.id_dosen', '=', 'dosen.id_dosen')
            ->leftJoin('ruangan', 'kelas.id_ruangan', '=', 'ruangan.id_ruangan')
            ->select(
                'krs.id_krs',
                'kelas.id_kelas',
                'kelas.tahun_ajaran',
                'kelas.tipe_kelas',
                'mata_kuliah.nama_mk',
                'mata_kuliah.sks',
                'dosen.nama_dosen',
                'ruangan.kode_ruangan',
                'ruangan.nama_ruangan',
                'ruangan.gedung',
                'ruangan.lantai'
            )
            ->get();

        // ID kelas yang sudah diambil (untuk filter di browse)
        $idKelasAktif = $krsAktif->pluck('id_kelas')->toArray();

        // Semua kelas tersedia yang BELUM diambil mahasiswa ini
        $kelasTersedia = DB::table('kelas')
            ->whereNotIn('kelas.id_kelas', $idKelasAktif)
            ->join('mata_kuliah', 'kelas.id_mk', '=', 'mata_kuliah.id_mk')
            ->leftJoin('dosen', 'kelas.id_dosen', '=', 'dosen.id_dosen')
            ->leftJoin('ruangan', 'kelas.id_ruangan', '=', 'ruangan.id_ruangan')
            ->select(
                'kelas.id_kelas',
                'kelas.tahun_ajaran',
                'kelas.tipe_kelas',
                'mata_kuliah.nama_mk',
                'mata_kuliah.sks',
                'dosen.nama_dosen',
                'ruangan.kode_ruangan',
                'ruangan.nama_ruangan',
                'ruangan.gedung',
                'ruangan.lantai',
                'ruangan.kapasitas'
            )
            ->get();

        return view('krs.index', compact('krsAktif', 'kelasTersedia', 'mhs'));
    }

    /**
     * POST /krs/tambah
     * Mahasiswa tambah kelas ke KRS
     */
    public function tambah(Request $request)
    {
        $mhs = session('mahasiswa');
        if (!$mhs) return redirect('/login');

        $id_mahasiswa = $mhs['id_mahasiswa'];
        $id_kelas     = $request->id_kelas;

        // Cek duplikat
        $sudahAda = DB::table('krs')
            ->where('id_mahasiswa', $id_mahasiswa)
            ->where('id_kelas', $id_kelas)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Kamu sudah mengambil kelas ini.');
        }

        // Generate id_krs
        $lastKrs = DB::table('krs')->orderBy('id_krs', 'desc')->value('id_krs');
        $lastNum = $lastKrs ? (int) substr($lastKrs, 3) : 0;
        $newId   = 'KRS' . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

        DB::table('krs')->insert([
            'id_krs'        => $newId,
            'id_mahasiswa'  => $id_mahasiswa,
            'id_kelas'      => $id_kelas,
            'created_at'    => now(),
        ]);

        return back()->with('success', 'Kelas berhasil ditambahkan ke KRS!');
    }

    /**
     * DELETE /krs/{id_krs}
     * Mahasiswa hapus kelas dari KRS
     */
    public function hapus($id_krs)
    {
        $mhs = session('mahasiswa');
        if (!$mhs) return redirect('/login');

        DB::table('krs')
            ->where('id_krs', $id_krs)
            ->where('id_mahasiswa', $mhs['id_mahasiswa']) // safety: hanya bisa hapus milik sendiri
            ->delete();

        return back()->with('success', 'Kelas berhasil dihapus dari KRS.');
    }
}