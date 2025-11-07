<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPelanggaran extends Model
{
    use HasFactory;

    protected $table = 'jenispelanggaran';
    protected $primaryKey = 'id_jenispelanggaran';
    public $timestamps = false;

    protected $fillable = [
        'id_kategoripelanggaran',
        'nama_pelanggaran',
        'deskripsi'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriPelanggaran::class, 'id_kategoripelanggaran', 'id_kategoripelanggaran');
    }
}
