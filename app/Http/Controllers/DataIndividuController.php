<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataIndividuController extends Controller
{
    public function index()
    {
        $mahasiswa = DB::table('dataindividu')->get();
        return view('dataindividu.index', compact('mahasiswa'));
    }

    public function create()
    {
        return view('dataindividu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
        ]);
        
        DB::table('dataindividu')->insert([
            'nomerindukmahasiswa' => $request->nim,
            'namamahasiswa' => $request->nama,
            'alamatmahasiswa' => $request->alamat,
        ]);

        return redirect('/dataindividu');
    }

    public function edit($nim)
    {
        $mhs = DB::table('dataindividu')
            ->where('nomerindukmahasiswa', $nim)
            ->first();

        return view('dataindividu.edit', compact('mhs'));
    }

    public function update(Request $request, $nim)
    {
        DB::table('dataindividu')
            ->where('nomerindukmahasiswa', $nim)
            ->update([
                'namamahasiswa' => $request->nama,
                'alamatmahasiswa' => $request->alamat,
            ]);

        return redirect('/dataindividu');
    }

    public function destroy($nim)
    {
        DB::table('dataindividu')
            ->where('nomerindukmahasiswa', $nim)
            ->delete();

        return redirect('/dataindividu');
    }

}

