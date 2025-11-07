<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use HasFactory;

    protected $table = 'user'; // <-- tabel kamu di database
    protected $primaryKey = 'id_user'; // <-- sesuaikan dengan nama kolom PK di tabel user
    public $timestamps = false; // ubah ke true kalau kamu punya kolom created_at dan updated_at

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}
