<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'tugas_akhirs';
    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'deskripsi',
        'file_path',
        'status',
        'catatan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_tugas_akhir', 'tugas_akhir_id', 'dosen_id')
                    ->withTimestamps();
    }

    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class, 'tugas_akhir_id');
    }
}