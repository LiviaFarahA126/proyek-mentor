<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'absenmahasiswa.mata_kuliah';

    protected $primaryKey = 'id_mk';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_mk',
        'nama_mk',
        'sks'
    ];
}
