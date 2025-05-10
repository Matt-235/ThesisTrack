@extends('layouts.app')

@section('title', 'Bimbingan')

@section('content_header')
    <h1>Bimbingan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Bimbingan</h3>
            <div class="float-right">
                @if (Auth::user()->role === 'dosen')
                    <a href="{{ route('bimbingan.create') }}" class="btn btn-primary btn-sm">Tambah Bimbingan</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="filter_mahasiswa">Filter Mahasiswa</label>
                    <select id="filter_mahasiswa" class="form-control select2">
                        <option value="" selected>Semua Mahasiswa</option>
                        @foreach ($bimbingans->pluck('tugasAkhir.mahasiswa.user.nama', 'tugasAkhir.mahasiswa_id')->unique() as $mahasiswaId => $nama)
                            <option value="{{ $mahasiswaId }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <table id="bimbinganTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tugas Akhir</th>
                        <th>Mahasiswa</th>
                        <th>Dosen</th>
                        <th>Tanggal</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bimbingans as $index => $bimbingan)
                        <tr data-mahasiswa-id="{{ $bimbingan->tugasAkhir->mahasiswa_id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $bimbingan->tugasAkhir->judul }}</td>
                            <td>{{ $bimbingan->tugasAkhir->mahasiswa->user->nama }}</td>
                            <td>{{ $bimbingan->tugasAkhir->dosen ? $bimbingan->tugasAkhir->dosen->user->nama : '-' }}</td>
                            <td>{{ $bimbingan->tanggal->format('d-m-Y') }}</td>
                            <td>{{ $bimbingan->catatan }}</td>
                            <td>
                                <a href="{{ route('bimbingan.show', $bimbingan) }}" class="btn btn-info btn-sm">Detail</a>
                                @if (Auth::user()->role === 'dosen' && Auth::user()->dosen->id === $bimbingan->tugasAkhir->dosen_id)
                                    <a href="{{ route('bimbingan.edit', $bimbingan) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('bimbingan.destroy', $bimbingan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus bimbingan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            var table = $("#bimbinganTable").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#bimbinganTable_wrapper .col-md-6:eq(0)');

            // Pastikan filter tidak aktif saat halaman dimuat
            table.column(2).search('').draw();

            $('#filter_mahasiswa').on('change', function () {
                var mahasiswaId = $(this).val();
                table.column(2).search(mahasiswaId ? '^' + mahasiswaId + '$' : '', true, false).draw();
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{!! session('success') !!}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@stop