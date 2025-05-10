@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content_header')
    <h1>Dashboard Mahasiswa</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Selamat Datang, {{ Auth::user()->nama }}</h3>
                    </div>
                    <div class="card-body">
                        <p>Ini adalah dashboard untuk mahasiswa. Anda dapat mengelola tugas akhir dan melihat catatan bimbingan Anda.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-book"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Tugas Akhir Anda</span>
                                        <span class="info-box-number">{{ Auth::user()->mahasiswa->tugasAkhirs->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-chalkboard-teacher"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Catatan Bimbingan</span>
                                        <span class="info-box-number">{{ Auth::user()->mahasiswa->tugasAkhirs->flatMap->bimbingans->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tabel Tugas Akhir -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h3 class="card-title">Tugas Akhir Anda</h3>
                            </div>
                            <div class="card-body">
                                <table id="tugasAkhirTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (Auth::user()->mahasiswa->tugasAkhirs as $index => $tugasAkhir)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $tugasAkhir->judul }}</td>
                                                <td>{{ ucfirst($tugasAkhir->status) }}</td>
                                                <td>
                                                    <a href="{{ route('tugas-akhir.show', $tugasAkhir) }}" class="btn btn-info btn-sm">Detail</a>
                                                    @if ($tugasAkhir->status === 'pending')
                                                        <a href="{{ route('tugas-akhir.edit', $tugasAkhir) }}" class="btn btn-warning btn-sm">Edit</a>
                                                        <form action="{{ route('tugas-akhir.destroy', $tugasAkhir) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tugas akhir ini?');">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            $("#tugasAkhirTable").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tugasAkhirTable_wrapper .col-md-6:eq(0)');

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