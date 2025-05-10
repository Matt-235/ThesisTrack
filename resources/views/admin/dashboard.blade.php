@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard Admin</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Selamat Datang, {{ Auth::user()->nama }}</h3>
                    </div>
                    <div class="card-body">
                        <p>Ini adalah dashboard untuk admin. Anda dapat mengelola semua tugas akhir dan bimbingan.</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Mahasiswa</span>
                                        <span class="info-box-number">{{ \App\Models\Mahasiswa::count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-book"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Tugas Akhir</span>
                                        <span class="info-box-number">{{ \App\Models\TugasAkhir::count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-chalkboard-teacher"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Bimbingan</span>
                                        <span class="info-box-number">{{ \App\Models\Bimbingan::count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop