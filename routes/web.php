<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TugasAkhirController;
use App\Http\Controllers\BimbinganController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });

    Route::middleware(['role:dosen'])->group(function () {
        Route::get('/dosen/dashboard', function () {
            return view('dosen.dashboard');
        })->name('dosen.dashboard');
    });

    Route::middleware(['role:mahasiswa'])->group(function () {
        Route::get('/mahasiswa/dashboard', function () {
            return view('mahasiswa.dashboard');
        })->name('mahasiswa.dashboard');
    });

    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Rute untuk Tugas Akhir
    Route::resource('tugas-akhir', TugasAkhirController::class);
    Route::post('tugas-akhir/{tugasAkhir}/approve', [TugasAkhirController::class, 'approve'])->name('tugas-akhir.approve');
    Route::post('tugas-akhir/{tugasAkhir}/reject', [TugasAkhirController::class, 'reject'])->name('tugas-akhir.reject');

    // Rute untuk Bimbingan
    Route::resource('bimbingan', BimbinganController::class);
});

require __DIR__.'/auth.php';