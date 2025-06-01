<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'user_id',
        'nip',
        'bidang_keahlian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tugasAkhirs()
    {
        return $this->belongsToMany(TugasAkhir::class, 'dosen_tugas_akhir', 'dosen_id', 'tugas_akhir_id')
                    ->withTimestamps();
    }
}