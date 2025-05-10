@extends('adminlte::page')

@section('title', 'Tambah Bimbingan')

@section('content_header')
    <h1>Tambah Bimbingan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Bimbingan</h3>
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
            <form action="{{ route('bimbingan.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="tugas_akhir_id">Tugas Akhir</label>
                    <select name="tugas_akhir_id" id="tugas_akhir_id" class="form-control select2" required>
                        <option value="">Pilih Tugas Akhir</option>
                        @foreach ($tugasAkhirs as $tugasAkhir)
                            <option value="{{ $tugasAkhir->id }}" {{ old('tugas_akhir_id') == $tugasAkhir->id ? 'selected' : '' }}>
                                {{ $tugasAkhir->judul }} ({{ $tugasAkhir->mahasiswa->user->nama }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="catatan">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="5" required>{{ old('catatan') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('bimbingan.index') }}" class="btn btn-secondary">Batal</a>
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