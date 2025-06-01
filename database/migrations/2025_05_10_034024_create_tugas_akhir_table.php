<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel tugas_akhirs
        Schema::create('tugas_akhirs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('file_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign key mahasiswa
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
        });

        // 2. Tabel pivot dosen_tugas_akhir
        Schema::create('dosen_tugas_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhirs')->onDelete('cascade');
            $table->timestamps();

            // Cegah duplikasi dosen-tugas_akhir
            $table->unique(['dosen_id', 'tugas_akhir_id']);
        });
    }

    public function down(): void
    {
        // Urutan drop dibalik untuk hindari masalah foreign key
        Schema::dropIfExists('dosen_tugas_akhir');
        Schema::dropIfExists('tugas_akhirs');
    }
};
