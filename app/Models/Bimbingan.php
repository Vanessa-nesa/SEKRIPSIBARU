<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    protected $table = 'bimbingan';
    protected $primaryKey = 'id_bimbingan';

    protected $fillable = [
        'NIS',
        'id_user',
        'tanggal',
        'tahunAjar',
        'bimbingan_ke',
        'notes',
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'NIS', 'NIS');
    }

    // Relasi ke user / guru BK
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
