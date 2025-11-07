<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPelanggaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenispelanggaran')->insert([
            // 🔹 RINGAN
            ['id_kategoripelanggaran' => 1, 'nama_pelanggaran' => 'Keterlambatan', 'deskripsi' => 'Datang terlambat ke sekolah'],
            ['id_kategoripelanggaran' => 1, 'nama_pelanggaran' => 'Tidak membawa perlengkapan sekolah', 'deskripsi' => 'Lupa alat tulis atau buku'],
            ['id_kategoripelanggaran' => 1, 'nama_pelanggaran' => 'Berpakaian tidak rapi', 'deskripsi' => 'Tidak mengenakan seragam sesuai aturan'],

            // 🔹 SEDANG
            ['id_kategoripelanggaran' => 2, 'nama_pelanggaran' => 'Salah Seragam', 'deskripsi' => 'Tidak memakai seragam sesuai ketentuan'],
            ['id_kategoripelanggaran' => 2, 'nama_pelanggaran' => 'Meninggalkan kelas tanpa izin', 'deskripsi' => 'Keluar tanpa izin guru'],
            ['id_kategoripelanggaran' => 2, 'nama_pelanggaran' => 'Bermain HP saat pelajaran', 'deskripsi' => 'Menggunakan ponsel di kelas'],

            // 🔹 BERAT
            ['id_kategoripelanggaran' => 3, 'nama_pelanggaran' => 'Mencuri', 'deskripsi' => 'Mengambil barang milik orang lain tanpa izin'],
            ['id_kategoripelanggaran' => 3, 'nama_pelanggaran' => 'Kekerasan fisik pada warga sekolah', 'deskripsi' => 'Melakukan pemukulan atau kekerasan'],
            ['id_kategoripelanggaran' => 3, 'nama_pelanggaran' => 'Merokok', 'deskripsi' => 'Merokok di area sekolah'],
            ['id_kategoripelanggaran' => 3, 'nama_pelanggaran' => 'Membolos melebihi batas ketentuan', 'deskripsi' => 'Tidak masuk sekolah melebihi batas yang diizinkan'],
        ]);
    }
}
