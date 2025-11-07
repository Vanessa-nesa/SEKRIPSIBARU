<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriPelanggaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategoripelanggaran')->insert([
            ['id_kategoripelanggaran' => 1, 'nama_kategori' => 'Ringan', 'deskripsi' => 'Pelanggaran ringan'],
            ['id_kategoripelanggaran' => 2, 'nama_kategori' => 'Sedang', 'deskripsi' => 'Pelanggaran sedang'],
            ['id_kategoripelanggaran' => 3, 'nama_kategori' => 'Berat', 'deskripsi' => 'Pelanggaran berat'],
        ]);
    }
}
