<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\Bimbingan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Dosen 1
        $dosenUser1 = User::create([
            'nama' => 'Dr. Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);
        $dosen1 = Dosen::create([
            'user_id' => $dosenUser1->id,
            'nip' => '123456789',
            'bidang_keahlian' => 'Informatika',
        ]);

        // Dosen 2
        $dosenUser2 = User::create([
            'nama' => 'Prof. Ani Rahayu',
            'email' => 'ani.rahayu@example.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);
        $dosen2 = Dosen::create([
            'user_id' => $dosenUser2->id,
            'nip' => '987654321',
            'bidang_keahlian' => 'Sistem Informasi',
        ]);

        // Mahasiswa 1
        $mahasiswaUser1 = User::create([
            'nama' => 'Ani Wijaya',
            'email' => 'ani.wijaya@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
        $mahasiswa1 = Mahasiswa::create([
            'user_id' => $mahasiswaUser1->id,
            'nim' => '12345678',
            'angkatan' => '2020',
        ]);

        // Mahasiswa 2
        $mahasiswaUser2 = User::create([
            'nama' => 'Bima Pratama',
            'email' => 'bima.pratama@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
        $mahasiswa2 = Mahasiswa::create([
            'user_id' => $mahasiswaUser2->id,
            'nim' => '87654321',
            'angkatan' => '2021',
        ]);

        // Tugas Akhir 1 (Mahasiswa 1, Dosen 1, Approved)
        $tugasAkhir1 = TugasAkhir::create([
            'mahasiswa_id' => $mahasiswa1->id,
            'dosen_id' => $dosen1->id,
            'judul' => 'Sistem Monitoring Tugas Akhir Berbasis Web',
            'deskripsi' => 'Membangun sistem untuk memantau progres tugas akhir mahasiswa.',
            'status' => 'approved',
            'file_path' => 'files/tugas_akhir_1.pdf',
            'catatan' => 'Judul disetujui, lanjutkan ke bab metodologi.',
        ]);

        // Tugas Akhir 2 (Mahasiswa 2, Dosen 2, Pending)
        $tugasAkhir2 = TugasAkhir::create([
            'mahasiswa_id' => $mahasiswa2->id,
            'dosen_id' => $dosen2->id,
            'judul' => 'Aplikasi Mobile untuk Manajemen Bimbingan',
            'deskripsi' => 'Mengembangkan aplikasi mobile untuk mendukung proses bimbingan tugas akhir.',
            'status' => 'pending',
            'file_path' => 'files/tugas_akhir_2.pdf',
            'catatan' => null,
        ]);

        // Bimbingan untuk Tugas Akhir 1
        Bimbingan::create([
            'tugas_akhir_id' => $tugasAkhir1->id,
            'catatan' => 'Revisi bab 1 dan 2 selesai, perbaiki struktur penulisan.',
            'tanggal' => now()->subDays(5),
        ]);
        Bimbingan::create([
            'tugas_akhir_id' => $tugasAkhir1->id,
            'catatan' => 'Perbaiki metodologi penelitian, tambahkan referensi terbaru.',
            'tanggal' => now()->subDays(2),
        ]);

        // Bimbingan untuk Tugas Akhir 2
        Bimbingan::create([
            'tugas_akhir_id' => $tugasAkhir2->id,
            'catatan' => 'Proposal awal diterima, perlu klarifikasi pada ruang lingkup.',
            'tanggal' => now()->subDays(3),
        ]);
    }
}