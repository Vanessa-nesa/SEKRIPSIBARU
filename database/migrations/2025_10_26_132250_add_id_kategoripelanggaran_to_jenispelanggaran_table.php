<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
{
    Schema::table('jenispelanggaran', function (Blueprint $table) {
        $table->unsignedBigInteger('id_kategoripelanggaran')->nullable()->after('nama_pelanggaran');
        $table->foreign('id_kategoripelanggaran')
              ->references('id_kategoripelanggaran')
              ->on('kategoripelanggaran')
              ->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenispelanggaran', function (Blueprint $table) {
            //
        });
    }
};
