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
        Schema::create('pengajuan_izin', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->date('tanggal_mulai')->comment('Tanggal mulai izin/sakit');
            $table->date('tanggal_selesai')->comment('Tanggal selesai izin/sakit');
            $table->integer('jumlah_hari')->comment('Total hari izin/sakit');
            $table->text('alasan')->comment('Alasan izin/sakit');
            $table->string('bukti_path', 255)->nullable()->comment('Path file bukti (surat dokter, dll)');
            $table->enum('jenis_izin', ['izin', 'sakit'])
                  ->comment('Jenis pengajuan: izin atau sakit');
            $table->enum('status_izin', ['pending', 'disetujui', 'ditolak'])
                  ->default('pending')
                  ->comment('Status approval dari wali kelas');

            $table->string('id_siswa', 20)->comment('NIS siswa yang mengajukan');
            $table->unsignedBigInteger('id_wali_kelas')->comment('ID wali kelas yang mereview');

            $table->text('catatan_wali')->nullable()->comment('Catatan dari wali kelas saat approve/reject');

            $table->index('id_siswa', 'idx_pengajuan_siswa');
            $table->index('status_izin', 'idx_pengajuan_status');
            $table->index('id_wali_kelas', 'idx_pengajuan_wali');
            $table->index(['tanggal_mulai', 'tanggal_selesai'], 'idx_pengajuan_tanggal');
            $table->index('jenis_izin', 'idx_pengajuan_jenis');

            $table->foreign('id_siswa', 'fk_pengajuan_siswa')
                  ->references('nis')
                  ->on('siswa')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->foreign('id_wali_kelas', 'fk_pengajuan_wali')
                  ->references('id_wali_kelas')
                  ->on('wali_kelas')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin');
    }
};