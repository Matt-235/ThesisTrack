@extends('adminlte::page')

@section('title', 'Detail Bimbingan')

@section('content_header')
    <h1>Detail Bimbingan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Bimbingan</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tugas Akhir:</strong> {{ $bimbingan->tugasAkhir->judul }}</p>
                    <p><strong>Mahasiswa:</strong> {{ $bimbingan->tugasAkhir->mahasiswa->user->nama }}</p>
                    <p><strong>Dosen:</strong> {{ $bimbingan->tugasAkhir->dosen ? $bimbingan->tugasAkhir->dosen->user->nama : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal:</strong> {{ $bimbingan->tanggal->format('d-m-Y') }}</p>
                    <p><strong>Catatan:</strong> {{ $bimbingan->catatan }}</p>
                </div>
            </div>
            <a href="{{ route('bimbingan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@stop