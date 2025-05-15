<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TugasAkhirController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\AdminUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rute untuk Admin
Route::prefix('admin')->middleware(['role:admin'])->group(function () {
Route::get('/dashboard', [AdminUserController::class, 'mahasiswaIndex'])->name('admin.dashboard');

// CRUD Mahasiswa
Route::get('/mahasiswa', [AdminUserController::class, 'mahasiswaIndex'])->name('admin.mahasiswa.index');
Route::post('/mahasiswa', [AdminUserController::class, 'mahasiswaStore'])->name('admin.mahasiswa.store');
Route::get('/mahasiswa/{mahasiswa}/edit', [AdminUserController::class, 'mahasiswaEdit'])->name('admin.mahasiswa.edit');
Route::put('/mahasiswa/{mahasiswa}', [AdminUserController::class, 'mahasiswaUpdate'])->name('admin.mahasiswa.update');
Route::delete('/mahasiswa/{mahasiswa}', [AdminUserController::class, 'mahasiswaDestroy'])->name('admin.mahasiswa.destroy');

// CRUD Dosen
Route::get('/dosen', [AdminUserController::class, 'dosenIndex'])->name('admin.dosen.index');
Route::post('/dosen', [AdminUserController::class, 'dosenStore'])->name('admin.dosen.store');
Route::get('/dosen/{dosen}/edit', [AdminUserController::class, 'dosenEdit'])->name('admin.dosen.edit');
Route::put('/dosen/{dosen}', [AdminUserController::class, 'dosenUpdate'])->name('admin.dosen.update');
Route::delete('/dosen/{dosen}', [AdminUserController::class, 'dosenDestroy'])->name('admin.dosen.destroy');
    });

// Rute untuk Dosen
Route::middleware(['role:dosen'])->group(function () {
Route::get('/dosen/dashboard', function () {
    return view('dosen.dashboard');
        })->name('dosen.dashboard');
    });

// Rute untuk Mahasiswa
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

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
});

require __DIR__.'/auth.php';