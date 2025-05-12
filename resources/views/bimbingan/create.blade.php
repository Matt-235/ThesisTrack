@extends('layouts.app')

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
                    <select name="tugas_akhir_id" id="tugas_akhir_id" class="form-control select2 @error('tugas_akhir_id') is-invalid @enderror" required>
                        <option value="">Pilih Tugas Akhir</option>
                        @if ($tugasAkhirs->count() > 0)
                            @foreach ($tugasAkhirs as $tugasAkhir)
                                <option value="{{ $tugasAkhir->id }}" {{ old('tugas_akhir_id') == $tugasAkhir->id ? 'selected' : '' }}>
                                    {{ $tugasAkhir->judul }} ({{ $tugasAkhir->mahasiswa->user->nama ?? 'Tidak ada nama' }})
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Tidak ada tugas akhir tersedia</option>
                        @endif
                    </select>
                    @error('tugas_akhir_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="catatan">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="5" required>{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" required>
                    @error('tanggal')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
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
            $('.select2').select2({
                placeholder: "Pilih Tugas Akhir",
                allowClear: true
            });
        });
    </script>
@stop