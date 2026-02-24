<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    protected $table      = 'pertemuan';
    protected $primaryKey = 'id_pertemuan';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'id_pertemuan',
        'id_kelas',
        'pertemuan_ke',
        'tanggal',
        'jam_mulai',   // ← fix: bukan 'jam'
        'jam_selesai', // ← fix: bukan 'jam'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_pertemuan', 'id_pertemuan');
    }
}