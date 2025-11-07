<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * 🔹 Tabel yang digunakan oleh model
     */
    protected $table = 'user';

    /**
     * 🔹 Primary key (karena bukan 'id')
     */
    protected $primaryKey = 'id_user';

    /**
     * 🔹 Nonaktifkan timestamps karena tabel tidak punya created_at & updated_at
     */
    public $timestamps = false;

    /**
     * 🔹 Kolom yang bisa diisi
     */
    protected $fillable = [
        'username',
        'password',
        'role',
        'nama',
    ];

    /**
     * 🔹 Kolom yang disembunyikan dari serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 🔹 Tidak ada casting tambahan selain password hash
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * 🔹 Relasi: satu user bisa punya banyak kelas (jika admin / wali)
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_user', 'id_user');
    }
}
