<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Pertemuan;
use App\Models\Absensi;

class PertemuanController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('matakuliah', 'dosen')->get();
        return view('pertemuan.index_kelas', compact('kelas'));
    }

    public function show($id_kelas)
    {
        $kelas     = Kelas::with('matakuliah', 'dosen')->findOrFail($id_kelas);
        $pertemuan = Pertemuan::where('id_kelas', $id_kelas)
                              ->orderBy('pertemuan_ke')
                              ->get();

        foreach ($pertemuan as $p) {
            $p->absensi_exists = Absensi::where('id_pertemuan', $p->id_pertemuan)->exists();
        }

        return view('pertemuan.index', compact('kelas', 'pertemuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kelas'     => 'required',
            'pertemuan_ke' => 'required|integer|min:1',
            'tanggal'      => 'required|date',
        ]);

        // ID urut PRT0001, PRT0002, dst — max 7 char, aman untuk VARCHAR(10)
        $last      = Pertemuan::orderByRaw("id_pertemuan DESC")->value('id_pertemuan');
        $lastNum   = $last ? (int) substr($last, 3) : 0;
        $newId     = 'PRT' . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

        Pertemuan::create([
            'id_pertemuan' => $newId,
            'id_kelas'     => $request->id_kelas,
            'pertemuan_ke' => $request->pertemuan_ke,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
        ]);

        return redirect('/pertemuan/' . $request->id_kelas)
               ->with('success', 'Pertemuan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pertemuan = Pertemuan::findOrFail($id);
        $pertemuan->update([
            'pertemuan_ke' => $request->pertemuan_ke,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
        ]);

        return redirect('/pertemuan/' . $pertemuan->id_kelas)
               ->with('success', 'Pertemuan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pertemuan = Pertemuan::findOrFail($id);
        $id_kelas  = $pertemuan->id_kelas;
        Absensi::where('id_pertemuan', $id)->delete();
        $pertemuan->delete();

        return redirect('/pertemuan/' . $id_kelas)
               ->with('success', 'Pertemuan berhasil dihapus.');
    }
}