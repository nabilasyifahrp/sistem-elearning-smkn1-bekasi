<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_izin', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->char('nis', 9);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('jenis_izin', ['sakit', 'izin']);
            $table->text('alasan');
            $table->string('bukti_file')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();

            $table->foreign('nis')->references('nis')->on('siswas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin');
    }
};