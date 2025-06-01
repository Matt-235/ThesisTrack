@extends('layouts.app')

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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('error') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tugas Akhir:</strong> {{ $bimbingan->tugasAkhir->judul }}</p>
                    <p><strong>Mahasiswa:</strong> {{ $bimbingan->mahasiswa->user->nama }}</p>
                    <p><strong>Dosen:</strong> {{ $bimbingan->dosen ? $bimbingan->dosen->user->nama : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($bimbingan->tanggal)->format('d-m-Y') }}</p>
                    <p><strong>Catatan:</strong> {{ $bimbingan->catatan }}</p>
                </div>
            </div>
            <a href="{{ route('bimbingan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@stop