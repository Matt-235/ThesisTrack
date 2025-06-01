<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDosenIdFromTugasAkhirsTable extends Migration
{
    // public function up()
    // {
    //     Schema::table('tugas_akhirs', function (Blueprint $table) {
    //         $table->dropForeign(['dosen_id']);
    //         $table->dropColumn('dosen_id');
    //     });
    // }

    // public function down()
    // {
    //     Schema::table('tugas_akhirs', function (Blueprint $table) {
    //         $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
    //     });
    // }
}