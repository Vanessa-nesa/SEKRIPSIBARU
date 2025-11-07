<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran';
    protected $primaryKey = 'id_pelanggaran';
    public $timestamps = false;

    protected $fillable = [
        'NIS',
        'id_user',
        'id_jenispelanggaran',
        'tanggal',
        'tahunAjar',
        'jumlah',
        'notes'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi ke tabel siswa (pakai kolom NIS)
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'NIS', 'NIS');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisPelanggaran::class, 'id_jenispelanggaran', 'id_jenispelanggaran');
    }
}
