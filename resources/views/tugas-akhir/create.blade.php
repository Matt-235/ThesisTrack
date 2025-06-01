@extends('layouts.app')

@section('title', 'Ajukan Tugas Akhir')

@section('content_header')
    <h1>Ajukan Tugas Akhir</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Pengajuan Tugas Akhir</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('tugas-akhir.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="judul">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul') }}" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="5" required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Dosen Pembimbing</label>
                    <div>
                        @foreach ($dosens as $dosen)
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    name="dosen_ids[]"
                                    value="{{ $dosen->id }}"
                                    id="dosen_{{ $dosen->id }}"
                                    class="form-check-input"
                                    {{ in_array($dosen->id, old('dosen_ids', [])) ? 'checked' : '' }}
                                >
                                <label for="dosen_{{ $dosen->id }}" class="form-check-label">
                                    {{ $dosen->user->nama }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <small class="form-text text-muted">Pilih maksimal 2 dosen pembimbing.</small>
                </div>

                <div class="form-group">
                    <label for="file">File Tugas Akhir (PDF/DOC/DOCX, maks 10MB)</label>
                    <input type="file" name="file" id="file" class="form-control-file" accept=".pdf,.doc,.docx">
                </div>

                <button type="submit" class="btn btn-primary">Ajukan</button>
                <a href="{{ route('tugas-akhir.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop
