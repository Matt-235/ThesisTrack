<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Admin
        $admin = User::create([
            'nama' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Dosen
        $dosen = User::create([
            'nama' => 'Dosen User',
            'email' => 'dosen@example.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        Dosen::create([
            'user_id' => $dosen->id,
            'nip' => '1234567890123456',
            'bidang_keahlian' => 'Sistem Informasi',
        ]);

        // Mahasiswa
        $mahasiswa = User::create([
            'nama' => 'Mahasiswa User',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $mahasiswa->id,
            'nim' => '1234567890',
            'angkatan' => '2020',
        ]);

        // Mahasiswa tambahan
        $mahasiswa2 = User::create([
            'nama' => 'Mahasiswa Dua',
            'email' => 'mahasiswa2@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $mahasiswa2->id,
            'nim' => '0987654321',
            'angkatan' => '2021',
        ]);
    }
}
