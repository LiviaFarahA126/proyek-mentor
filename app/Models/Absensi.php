<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{

    protected $table = 'absenmahasiswa.absensi';

    protected $primaryKey = 'id_absensi';

    public $incrementing = false;

    protected $fillable = [
        'id_absensi',
        'id_pertemuan',
        'id_mahasiswa',
        'status'
    ];


    /*
    ====================
    RELATION
    ====================
    */

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class,'id_pertemuan');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class,'id_mahasiswa','id_mahasiswa');
    }
}
