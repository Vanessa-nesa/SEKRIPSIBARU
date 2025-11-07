<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    protected $table = 'bimbingan';
    protected $primaryKey = 'id_bimbingan';
    public $timestamps = true; // karena ada created_at dan updated_at

    protected $fillable = [
        'NIS',
        'id_user',
        'tanggal',
        'kehadiran',
        'pelanggaran',
        'bimbingan_ke', // diganti dari 'bimbingan-ke' biar sesuai standar nama kolom
        'notes',
        'tahunAjar',
    ];

    /**
     * Relasi ke tabel siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'NIS', 'NIS');
    }

    /**
     * Relasi ke tabel pengguna (guru BK)
     */
    public function guruBK()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
