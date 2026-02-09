<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'absenmahasiswa.mahasiswa';

    protected $primaryKey = 'id_mahasiswa';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_mahasiswa',
        'nim',
        'nama_mahasiswa',
        'alamat'
    ];    
}
