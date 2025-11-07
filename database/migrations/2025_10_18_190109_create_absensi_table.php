<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->unsignedBigInteger('NIS'); // relasi ke siswa
            $table->date('tanggal'); // tanggal absensi
            $table->enum('status', ['H', 'S', 'I', 'A']); // Hadir, Sakit, Izin, Alpha
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('NIS')->references('NIS')->on('siswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
