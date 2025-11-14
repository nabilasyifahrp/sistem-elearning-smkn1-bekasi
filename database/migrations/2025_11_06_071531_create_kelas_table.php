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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->enum('tingkat', ['X', 'XI', 'XII'])->nullable(false);
            $table->enum('jurusan', ['RPL', 'DKV', 'TKJ', 'AK', 'BB', 'TP', 'TKR', 'TPL'])->nullable(false);
            $table->enum('kelas', ['A', 'B', 'C'])->nullable(false);
            $table->integer('jumlah_siswa')->nullable(true);
            $table->string('tahun_ajaran', 9)->nullable(false);
            $table->unique(['tingkat', 'jurusan', 'kelas', 'tahun_ajaran']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
