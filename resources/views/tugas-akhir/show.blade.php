@extends('layouts.app')

@section('title', 'Detail Tugas Akhir')

@section('content_header')
    <h1>Detail Tugas Akhir</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Tugas Akhir</h3>
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
                    <p><strong>Judul:</strong> {{ $tugasAkhir->judul }}</p>
                    <p><strong>Mahasiswa:</strong> {{ $tugasAkhir->mahasiswa->user->nama }}</p>
                    <p><strong>Dosen Pembimbing:</strong>
                        @if ($tugasAkhir->dosens->isNotEmpty())
                            @foreach ($tugasAkhir->dosens as $dosen)
                                {{ $dosen->user->nama }}<br>
                            @endforeach
                        @else
                            -
                        @endif
                    </p>
                    <p><strong>Status:</strong>
                        @if ($tugasAkhir->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif ($tugasAkhir->status === 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Deskripsi:</strong> {{ $tugasAkhir->deskripsi }}</p>
                    <p><strong>File:</strong>
                        @if ($tugasAkhir->file_path)
                            <a href="{{ Storage::url($tugasAkhir->file_path) }}" target="_blank">{{ basename($tugasAkhir->file_path) }}</a>
                        @else
                            Tidak ada file
                        @endif
                    </p>
                    <p><strong>Catatan:</strong> {{ $tugasAkhir->catatan ?? '-' }}</p>
                </div>
            </div>
            <a href="{{ route('tugas-akhir.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@stop