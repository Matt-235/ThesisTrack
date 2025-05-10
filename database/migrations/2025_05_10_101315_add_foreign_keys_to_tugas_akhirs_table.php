<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas_akhirs', function (Blueprint $table) {
            $table->foreign('mahasiswa_id', 'fk_tugas_akhirs_mahasiswa')
                  ->references('id')
                  ->on('mahasiswa')
                  ->onDelete('cascade');
            $table->foreign('dosen_id', 'fk_tugas_akhirs_dosen')
                  ->references('id')
                  ->on('dosen')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tugas_akhirs', function (Blueprint $table) {
            $table->dropForeign('fk_tugas_akhirs_mahasiswa');
            $table->dropForeign('fk_tugas_akhirs_dosen');
        });
    }
};