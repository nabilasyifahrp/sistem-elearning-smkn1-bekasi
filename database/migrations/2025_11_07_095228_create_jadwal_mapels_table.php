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
        Schema::create('jadwal_mapels', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'])->nullable(false);
            $table->time('jam_mulai')->nullable(false);
            $table->time('jam_selesai')->nullable(false);
            $table->enum('tipe', ['Teori', 'Tefa'])->nullable(false);
            $table->string('tahun_ajaran', 9)->nullable(false);
            $table->foreignId('id_kelas')->constrained(table: 'kelas', column: 'id_kelas')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_guru_mapel')->constrained(table: 'guru_mapels', column: 'id_guru_mapel')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['hari', 'jam_mulai', 'id_kelas', 'tahun_ajaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_mapels');
    }
};
