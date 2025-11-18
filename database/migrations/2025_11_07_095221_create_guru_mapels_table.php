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
        Schema::create('guru_mapels', function (Blueprint $table) {
            $table->id('id_guru_mapel');
            $table->char('tahun_ajaran', 9)->nullable(false);
            $table->foreignId('id_guru')->constrained(table: 'gurus', column: 'id_guru')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_mapel')->constrained(table: 'mapels', column: 'id_mapel')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_kelas')->constrained(table: 'kelas', column: 'id_kelas')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['tahun_ajaran', 'id_guru', 'id_mapel', 'id_kelas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_mapels');
    }
};
