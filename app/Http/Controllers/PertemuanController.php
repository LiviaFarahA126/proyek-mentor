<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PertemuanController extends Controller
{
    public function show($kelas_id,$pertemuan_id)
    {
        // ambil semua mahasiswa + status absensi
        $data = DB::table('absenmahasiswa.mahasiswa as m')
            ->leftJoin('absenmahasiswa.absensi as a', function($join) use ($pertemuan_id){
                $join->on('m.id_mahasiswa','=','a.id_mahasiswa')
                     ->where('a.id_pertemuan','=',$pertemuan_id);
            })
            ->select(
                'm.id_mahasiswa',
                'm.nama_mahasiswa',
                'a.status'
            )
            ->get();

        return view('pertemuan.absensi', compact('data','pertemuan_id'));
    }
}
