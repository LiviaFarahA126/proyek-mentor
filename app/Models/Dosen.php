<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';

    protected $primaryKey = 'id_dosen';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_dosen',
        'nik',
        'nama_dosen',
        'email',
        'password'
    ];

    protected $hidden = [
        'password'
    ];
}
