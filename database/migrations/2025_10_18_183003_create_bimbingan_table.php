<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->id('id_bimbingan');
            $table->unsignedBigInteger('NIS'); // relasi ke siswa
            $table->string('kehadiran');
            $table->string('pelanggaran');
            $table->integer('bimbingan-ke');
            $table->string('notes')->nullable();
            $table->timestamps();

            // Relasi foreign key ke tabel siswa
            $table->foreign('NIS')->references('NIS')->on('siswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbingan');
    }
};
