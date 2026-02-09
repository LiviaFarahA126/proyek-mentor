<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function byMataKuliah($id)
    {
        $kelas = DB::table('absenmahasiswa.kelas')
            ->where('id_mk',$id)
            ->get();

        return view('kelas.index', compact('kelas'));
    }

    public function show($id)
    {
        $pertemuan = DB::table('absenmahasiswa.pertemuan')
            ->where('id_kelas',$id)
            ->get();

        return view('kelas.show', compact('pertemuan'));
    }
}
