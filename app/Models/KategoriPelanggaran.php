<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPelanggaran extends Model
{
    use HasFactory;

    protected $table = 'kategoripelanggaran';
    protected $primaryKey = 'id_kategoripelanggaran';
    public $timestamps = false;

    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function jenis()
    {
        return $this->hasMany(JenisPelanggaran::class, 'id_kategoripelanggaran', 'id_kategoripelanggaran');
    }
}
