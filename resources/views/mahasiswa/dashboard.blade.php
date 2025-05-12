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
                        <!-- Statistik -->
                        @php
                            $tugasAkhirs = Auth::user()->mahasiswa
                                ? \App\Models\TugasAkhir::where('mahasiswa_id', Auth::user()->mahasiswa->id)->get()
                                : collect([]);
                            \Illuminate\Support\Facades\Log::info('Dashboard Mahasiswa', [
                                'user_id' => Auth::id(),
                                'user_nama' => Auth::user()->nama,
                                'mahasiswa_id' => Auth::user()->mahasiswa ? Auth::user()->mahasiswa->id : null,
                                'tugas_akhirs_count' => $tugasAkhirs->count(),
                                'tugas_akhirs' => $tugasAkhirs->toArray(),
                            ]);
                        @endphp
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-book"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Tugas Akhir</span>
                                        <span class="info-box-number">{{ $tugasAkhirs->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-sticky-note"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Catatan Bimbingan</span>
                                        <span class="info-box-number">{{ $tugasAkhirs->flatMap->bimbingans->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Tugas Akhir Pending</span>
                                        <span class="info-box-number">{{ $tugasAkhirs->where('status', 'pending')->count() }}</span>
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
                                @if ($tugasAkhirs->count() > 0)
                                    <table id="tugasAkhirTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tugasAkhirs as $index => $tugasAkhir)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $tugasAkhir->judul ?? '-' }}</td>
                                                    <td>{{ $tugasAkhir->dosen ? $tugasAkhir->dosen->user->nama : '-' }}</td>
                                                    <td>{{ ucfirst($tugasAkhir->status ?? 'tidak ada status') }}</td>
                                                    <td>
                                                        <a href="{{ route('tugas-akhir.show', $tugasAkhir->id) }}" class="btn btn-info btn-sm">Detail</a>
                                                        @if ($tugasAkhir->status === 'pending')
                                                            <a href="{{ route('tugas-akhir.edit', $tugasAkhir->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                            <form action="{{ route('tugas-akhir.destroy', $tugasAkhir->id) }}" method="POST" style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas akhir ini?')">Hapus</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>Belum ada tugas akhir yang terdaftar.</p>
                                @endif
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
