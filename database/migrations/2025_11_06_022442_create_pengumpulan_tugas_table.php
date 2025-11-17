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
            $table->string('file_path', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['belum_dinilai', 'sudah_dinilai'])
                  ->default('belum_dinilai')
                  ->comment('Status penilaian tugas');
            $table->timestamp('tanggal_kumpul')->useCurrent();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('feedback')->nullable;
            
            $table->unsignedBigInteger('id_tugas');
            $table->string('id_siswa', 20);
            
            $table->index('id_tugas', 'idx_pengumpulan_tugas');
            $table->index('id_siswa', 'idx_pengumpulan_siswa');
            $table->index('status', 'idx_pengumpulan_status');
            $table->index('tanggal_kumpul', 'idx_pengumpulan_tanggal');

            $table->unique(['id_tugas', 'id_siswa'], 'unique_pengumpulan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
