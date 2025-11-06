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
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id('id_pengumpulan');
            $table->string('file_path', 255)->nullable()->comment('Path file yang diupload siswa');
            $table->text('keterangan')->nullable()->comment('Keterangan atau catatan dari siswa');
            $table->enum('status', ['belum_dinilai', 'sudah_dinilai'])
                  ->default('belum_dinilai')
                  ->comment('Status penilaian tugas');
            $table->timestamp('tanggal_kumpul')->useCurrent()->comment('Tanggal siswa mengumpulkan tugas');
            $table->decimal('nilai', 5, 2)->nullable()->comment('Nilai tugas (0-100)');
            $table->text('feedback')->nullable()->comment('Feedback dari guru');
            
            $table->unsignedBigInteger('id_tugas')->comment('ID tugas yang dikumpulkan');
            $table->string('id_siswa', 20)->comment('NIS siswa yang mengumpulkan');
            
            $table->index('id_tugas', 'idx_pengumpulan_tugas');
            $table->index('id_siswa', 'idx_pengumpulan_siswa');
            $table->index('status', 'idx_pengumpulan_status');
            $table->index('tanggal_kumpul', 'idx_pengumpulan_tanggal');

            $table->foreign('id_tugas', 'fk_pengumpulan_tugas')
                  ->references('id_tugas')
                  ->on('tugas')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->foreign('id_siswa', 'fk_pengumpulan_siswa')
                  ->references('nis')
                  ->on('siswa')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            $table->timestamps();

            $table->unique(['id_tugas', 'id_siswa'], 'unique_pengumpulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};