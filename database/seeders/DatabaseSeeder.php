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
        User::create([
            'nama' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Dosen 1
        $dosen1 = User::create([
            'nama' => 'Dosen Satu',
            'email' => 'dosen1@example.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        Dosen::create([
            'user_id' => $dosen1->id,
            'nip' => '1111111111111111',
            'bidang_keahlian' => 'Rekayasa Perangkat Lunak',
        ]);

        // Dosen 2
        $dosen2 = User::create([
            'nama' => 'Dosen Dua',
            'email' => 'dosen2@example.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        Dosen::create([
            'user_id' => $dosen2->id,
            'nip' => '2222222222222222',
            'bidang_keahlian' => 'Kecerdasan Buatan',
        ]);

        // Mahasiswa 1
        $mahasiswa1 = User::create([
            'nama' => 'Mahasiswa Satu',
            'email' => 'mahasiswa1@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $mahasiswa1->id,
            'nim' => '2020000001',
            'angkatan' => '2020',
        ]);

        // Mahasiswa 2
        $mahasiswa2 = User::create([
            'nama' => 'Mahasiswa Dua',
            'email' => 'mahasiswa2@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $mahasiswa2->id,
            'nim' => '2021000002',
            'angkatan' => '2021',
        ]);
    }
}
