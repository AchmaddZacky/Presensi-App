<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketidakhadiran extends Model
{
    use HasFactory;

    protected $table = 'ketidakhadiran';

    protected $fillable = [
        'id_pegawai',
        'keterangan',
        'tanggal',
        'deskripsi',
        'file',
        'status_pengajuan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function getDownloadFilenameAttribute(): string
    {
        $kondisi = ucfirst($this->keterangan ?? 'Izin');
        $nama = $this->pegawai->nama ?? 'Pegawai';
        $tanggalUnduh = now()->format('d-m-Y');
        $ext = pathinfo($this->file ?? '', PATHINFO_EXTENSION) ?: 'pdf';

        // Bersihkan karakter terlarang untuk nama file pada sistem operasi
        $safeKondisi = trim(preg_replace('/[\\/\\\\:*?"<>|]/', '', $kondisi));
        $safeNama = trim(preg_replace('/[\\/\\\\:*?"<>|]/', '', $nama));

        return "Lampiran {$safeKondisi} {$safeNama} {$tanggalUnduh}.{$ext}";
    }
}
