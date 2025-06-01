@extends('layouts.app')

@section('title', 'Daftar Bimbingan')

@section('content_header')
    <h1>Daftar Bimbingan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Bimbingan</h3>
            @if (Auth::user()->role === 'dosen')
                <div class="float-right">
                    <a href="{{ route('bimbingan.create') }}" class="btn btn-primary btn-sm">Tambah Bimbingan</a>
                </div>
            @endif
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
                        <tr data-mahasiswa-id="{{ $bimbingan->mahasiswa_id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $bimbingan->tugasAkhir->judul }}</td>
                            <td>{{ $bimbingan->mahasiswa->user->nama }}</td>
                            <td>{{ $bimbingan->dosen ? $bimbingan->dosen->user->nama : '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($bimbingan->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $bimbingan->catatan }}</td>
                            <td>
                                <a href="{{ route('bimbingan.show', $bimbingan) }}" class="btn btn-info btn-sm">Detail</a>
                                @if (Auth::user()->role === 'dosen' && Auth::user()->dosen->id === $bimbingan->dosen_id)
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
            $("#bimbinganTable").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#bimbinganTable_wrapper .col-md-6:eq(0)');

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