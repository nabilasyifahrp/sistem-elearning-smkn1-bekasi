<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
            $table->id('id_materi');
            $table->foreignId('id_guru_mapel')->constrained('guru_mapels', 'id_guru_mapel')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('judul_materi', 150);
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();
            $table->date('tanggal_upload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
