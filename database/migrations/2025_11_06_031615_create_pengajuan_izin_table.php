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
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari');
            $table->text('alasan');
            $table->string('bukti_path', 255)->nullable();
            $table->enum('jenis_izin', ['izin', 'sakit']);
            $table->enum('status_izin', ['pending', 'disetujui', 'ditolak'])
                  ->default('pending');
            
            $table->string('id_siswa', 20);
            $table->unsignedBigInteger('id_wali_kelas');

            $table->text('catatan_wali')->nullable();
            
            $table->index('id_siswa', 'idx_pengajuan_siswa');
            $table->index('status_izin', 'idx_pengajuan_status');
            $table->index('id_wali_kelas', 'idx_pengajuan_wali');
            $table->index(['tanggal_mulai', 'tanggal_selesai'], 'idx_pengajuan_tanggal');
            $table->index('jenis_izin', 'idx_pengajuan_jenis');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin');
    }
};
