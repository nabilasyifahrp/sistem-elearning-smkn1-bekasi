<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id('id_pengumpulan');
            $table->unsignedBigInteger('id_tugas');
            $table->bigInteger('nis');
            $table->text('isi_tugas');
            $table->text('file_path')->nullable();
            $table->dateTime('tanggal_pengumpulan');
            $table->integer('nilai')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->foreign('id_tugas')->references('id_tugas')->on('tugas')->onDelete('cascade');
            $table->foreign('nis')->references('nis')->on('siswas')->onDelete('cascade')->onUpdate('cascade');
            
            // Pastikan satu siswa hanya bisa mengumpulkan tugas sekali
            $table->unique(['id_tugas', 'nis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};