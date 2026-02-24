<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Pertemuan;

class KelasController extends Controller
{

    public function index()
    {
        $kelas = Kelas::with(['matakuliah','dosen'])->get();
        $matakuliah = Matakuliah::all();
        $dosen = Dosen::all();

        return view('kelas.index',compact('kelas','matakuliah','dosen'));
    }

    public function manage($id)
    {
        $kelas = Kelas::with('matakuliah','dosen')->findOrFail($id);
    
        $pertemuan = Pertemuan::where('id_kelas',$id)->get();
    
        return view('kelas.manage',compact('kelas','pertemuan'));
    }    

    public function store(Request $request)
    {
    
        $mk = MataKuliah::findOrFail($request->id_mk);
    
        // ambil huruf depan tiap kata
        $words = explode(' ', $mk->nama_mk);
    
        $prefix = '';
    
        foreach($words as $w){
            $prefix .= strtoupper(substr($w,0,1));
        }
    
        // ambil max 2 huruf saja biar clean
        $prefix = substr($prefix,0,2);
    
        // cari kelas terakhir dengan prefix sama
        $last = Kelas::where('id_kelas','like',$prefix.'%')
                ->orderBy('id_kelas','desc')
                ->first();
    
        if($last){
            $num = (int) substr($last->id_kelas,2) + 1;
        }else{
            $num = 1;
        }
    
        $id = $prefix.str_pad($num,3,'0',STR_PAD_LEFT);
    
        Kelas::create([
            'id_kelas'=>$id,
            'id_mk'=>$request->id_mk,
            'id_dosen'=>$request->id_dosen,
            'tahun_ajaran'=>$request->tahun_ajaran
        ]);
    
        return redirect('/kelas');
    }
    
    public function update(Request $request,$id)
    {
        $kelas = Kelas::findOrFail($id);
    
        $kelas->update([
            'id_mk'=>$request->id_mk,
            'id_dosen'=>$request->id_dosen,
            'tahun_ajaran'=>$request->tahun_ajaran
        ]);
    
        return redirect('/kelas');
    }
    
    public function destroy($id)
    {
        Kelas::destroy($id);
        return redirect('/kelas');
    }
}
