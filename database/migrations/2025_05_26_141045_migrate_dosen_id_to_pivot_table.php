<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateDosenIdToPivotTable extends Migration
{
    // public function up()
    // {
    //     $tugasAkhirs = DB::table('tugas_akhirs')->select('id', 'dosen_id')->get();
    //     foreach ($tugasAkhirs as $tugasAkhir) {
    //         if ($tugasAkhir->dosen_id) {
    //             DB::table('dosen_tugas_akhir')->insert([
    //                 'dosen_id' => $tugasAkhir->dosen_id,
    //                 'tugas_akhir_id' => $tugasAkhir->id,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }
    //     }
    // }

    // public function down()
    // {
    //     // Kosongkan tabel pivot jika rollback
    //     DB::table('dosen_tugas_akhir')->truncate();
    // }
}