<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'NIS';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
    'NIS',
    'nama_siswa',
    'kelas_siswa',
    'jurusan_siswa',
    'tahunAjar',
    'id_kelas',
    ];

    // 🔹 Relasi ke tabel Prestasi
    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'NIS', 'NIS');
    }

    // 🔹 Relasi ke tabel Pelanggaran
    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'NIS', 'NIS');
    }

    // 🔹 Relasi ke tabel Kelas
    public function kelas()
    {
    return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

}
