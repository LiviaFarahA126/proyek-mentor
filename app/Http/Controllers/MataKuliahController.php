<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;

class MataKuliahController extends Controller
{

    public function index()
    {
        $mk = MataKuliah::orderBy('id_mk')->get();
        return view('mata_kuliah.index', compact('mk'));
    }

    public function store(Request $request)
    {
        $last = MataKuliah::orderBy('id_mk','desc')->first();

        $num = $last ? ((int) substr($last->id_mk,2) + 1) : 1;

        $id = 'MK'.str_pad($num,4,'0',STR_PAD_LEFT);

        MataKuliah::create([
            'id_mk'=>$id,
            'nama_mk'=>$request->nama_mk,
            'sks'=>$request->sks
        ]);

        return back();
    }

    public function update(Request $request,$id)
    {
        $mk = MataKuliah::findOrFail($id);

        $mk->update([
            'nama_mk'=>$request->nama_mk,
            'sks'=>$request->sks
        ]);

        return back();
    }

    public function destroy($id)
    {
        MataKuliah::destroy($id);
        return back();
    }
}
