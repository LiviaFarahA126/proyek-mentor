<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function store(Request $request)
    {
        foreach($request->status as $id_mahasiswa => $status)
        {
            DB::table('absenmahasiswa.absensi')
            ->updateOrInsert(
                [
                    'id_pertemuan'=>$request->id_pertemuan,
                    'id_mahasiswa'=>$id_mahasiswa
                ],
                [
                    'status'=>$status
                ]
            );
        }

        return back()->with('success','Absensi disimpan');
    }
}
