@extends('layouts.app')

@section('title', 'Edit Tugas Akhir')

@section('content_header')
    <h1>Edit Tugas Akhir</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Tugas Akhir</h3>
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
            <form action="{{ route('tugas-akhir.update', $tugasAkhir) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="judul">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $tugasAkhir->judul) }}" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="5" required>{{ old('deskripsi', $tugasAkhir->deskripsi) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="dosen_ids">Dosen Pembimbing</label>
                    <select name="dosen_ids[]" id="dosen_ids" class="form-control select2" multiple="multiple" required>
                        <option value="">Pilih Dosen</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ in_array($dosen->id, old('dosen_ids', $tugasAkhir->dosens->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $dosen->user->nama }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Pilih satu atau lebih dosen pembimbing (maksimal 2).</small>
                </div>
                <div class="form-group">
                    <label for="file">File Tugas Akhir (PDF/DOC/DOCX, maks 10MB)</label>
                    <input type="file" name="file" id="file" class="form-control-file" accept=".pdf,.doc,.docx">
                    @if ($tugasAkhir->file_path)
                        <p>File saat ini: <a href="{{ Storage::url($tugasAkhir->file_path) }}" target="_blank">{{ basename($tugasAkhir->file_path) }}</a></p>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('tugas-akhir.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Dosen",
                allowClear: true
            });
        });
    </script>
@stop