<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->char('nis', 9);
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_pengajuan')->nullable();
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa']);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
            $table->foreign('nis')->references('nis')->on('siswas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_mapels')->onDelete('cascade');
            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan_izin')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
