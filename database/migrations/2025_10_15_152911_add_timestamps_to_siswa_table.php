<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi: tambahkan kolom timestamps ke tabel siswa.
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Tambahkan kolom created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi: hapus kolom timestamps dari tabel siswa.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
