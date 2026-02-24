<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{

    public function index()
    {
        $dosen = Dosen::all();

        return view('dosen.index', compact('dosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dosen' => 'required|unique:dosen,id_dosen',
            'nik' => 'required',
            'nama_dosen' => 'required',
            'email' => 'required|email|unique:dosen,email',
            'password' => 'required'
        ]);

        Dosen::create([
            'id_dosen' => $request->id_dosen,
            'nik' => $request->nik,
            'nama_dosen' => $request->nama_dosen,
            'email' => $request->email,
            'password' => Hash::make($request->password) // siap login nanti
        ]);

        return redirect()->back()->with('success','Data dosen berhasil ditambah');
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $data = [
            'nik' => $request->nik,
            'nama_dosen' => $request->nama_dosen,
            'email' => $request->email
        ];

        // kalau password diisi baru update
        if($request->password){
            $data['password'] = Hash::make($request->password);
        }

        $dosen->update($data);

        return redirect()->back()->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Dosen::findOrFail($id)->delete();

        return redirect()->back()->with('success','Data berhasil dihapus');
    }
}
