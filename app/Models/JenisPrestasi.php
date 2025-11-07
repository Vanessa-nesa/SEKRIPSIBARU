<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPrestasi extends Model
{
    protected $table = 'jenisprestasi';
    protected $primaryKey = 'id_jenisprestasi';
    public $timestamps = false;

    protected $fillable = ['id_kategoriprestasi', 'nama_jenis'];

    public function kategori()
    {
        return $this->belongsTo(KategoriPrestasi::class, 'id_kategoriprestasi');
    }
}

