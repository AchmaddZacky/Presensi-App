<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'nrg',
        'nama',
        'jenis_kelamin',
        'alamat',
        'no_handphone',
        'jabatan_id',
        'lokasi_presensi_id',
        'foto',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id_pegawai');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function lokasiPresensi()
    {
        return $this->belongsTo(LokasiPresensi::class, 'lokasi_presensi_id');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_pegawai');
    }

    public function ketidakhadiran()
    {
        return $this->hasMany(Ketidakhadiran::class, 'id_pegawai');
    }
}
