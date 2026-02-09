<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class DataIndividuController extends Controller
{

    public function index()
    {
        $mhs = Mahasiswa::orderBy('id_mahasiswa')->get();
        return view('mahasiswa.index', compact('mhs'));
    }
    
    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        $last = Mahasiswa::orderBy('id_mahasiswa','desc')->first();
    
        $num = $last ? ((int) substr($last->id_mahasiswa,3) + 1) : 1;
    
        $id = 'MHS'.str_pad($num,4,'0',STR_PAD_LEFT);
    
        Mahasiswa::create([
            'id_mahasiswa'=>$id,
            'nim'=>$request->nim,
            'nama_mahasiswa'=>$request->nama_mahasiswa,
            'alamat'=>$request->alamat
        ]);
    
        return back();
    }
    
    public function edit($id)
    {
        $mhs = Mahasiswa::findOrFail($id);
        return view('mahasiswa.edit', compact('mhs'));
    }

    public function update(Request $request,$id)
    {
        $mhs = Mahasiswa::findOrFail($id);
    
        $mhs->update([
            'nim'=>$request->nim,
            'nama_mahasiswa'=>$request->nama_mahasiswa,
            'alamat'=>$request->alamat
        ]);
    
        return back();
    }
    
    public function destroy($id)
    {
        Mahasiswa::destroy($id);
        return back();
    }
    
}