<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPrestasi extends Model
{
    protected $table = 'kategoriprestasi';
    protected $primaryKey = 'id_kategoriprestasi';
    public $timestamps = false;

    protected $fillable = ['nama_kategori'];

    public function jenis()
    {
        return $this->hasMany(JenisPrestasi::class, 'id_kategoriprestasi');
    }
}

