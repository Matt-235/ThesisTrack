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
                    <label for="dosen_id">Dosen Pembimbing</label>
                    <select name="dosen_id" id="dosen_id" class="form-control select2" required>
                        <option value="">Pilih Dosen</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>{{ $dosen->user->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="file">File Tugas Akhir (PDF/DOC/DOCX, maks 10MB)</label>
                    <input type="file" name="file" id="file" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary">Ajukan</button>
                <a href="{{ route('tugas-akhir.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@stop