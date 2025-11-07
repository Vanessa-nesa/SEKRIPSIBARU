<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';
    protected $primaryKey = 'id_prestasi';
    public $timestamps = false;

    protected $fillable = [
        'NIS',
        'kelas',
        'jurusan',
        'id_user',
        'id_jenisprestasi',
        'tanggal',
        'tahunAjar',
        'file_prestasi'
    ];

    /**
     * 🔹 Relasi ke Jenis Prestasi
     * Setiap prestasi punya satu jenis
     */
    public function jenis()
    {
        return $this->belongsTo(JenisPrestasi::class, 'id_jenisprestasi', 'id_jenisprestasi');
    }

    /**
     * 🔹 Relasi ke Siswa
     * Setiap prestasi dimiliki oleh satu siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'NIS', 'NIS');
    }
}
