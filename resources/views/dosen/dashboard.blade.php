    @extends('layouts.app')

    @section('title', 'Dashboard Dosen')

    @section('content_header')
        <h1>Dashboard Dosen</h1>
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
                            <p>Ini adalah dashboard untuk dosen. Anda dapat melihat tugas akhir yang Anda bimbing dan catatan bimbingan.</p>
                            <!-- Statistik -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-book"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tugas Akhir Dibimbing</span>
                                            <span class="info-box-number">{{ Auth::user()->dosen->tugasAkhirs->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-chalkboard-teacher"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Catatan Bimbingan</span>
                                            <span class="info-box-number">{{ Auth::user()->dosen->tugasAkhirs->flatMap->bimbingans->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tugas Akhir Pending</span>
                                            <span class="info-box-number">{{ Auth::user()->dosen->tugasAkhirs->where('status', 'pending')->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tabel Tugas Akhir -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Tugas Akhir yang Dibimbing</h3>
                                </div>
                                <div class="card-body">
                                    <table id="tugasAkhirTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul</th>
                                                <th>Mahasiswa</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (Auth::user()->dosen->tugasAkhirs as $index => $tugasAkhir)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $tugasAkhir->judul }}</td>
                                                    <td>{{ $tugasAkhir->mahasiswa->user->nama }}</td>
                                                    <td>{{ ucfirst($tugasAkhir->status) }}</td>
                                                    <td>
                                                        <a href="{{ route('tugas-akhir.show', $tugasAkhir) }}" class="btn btn-info btn-sm">Detail</a>
                                                        @if ($tugasAkhir->status === 'pending')
                                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveModal{{ $tugasAkhir->id }}">Setujui</button>
                                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal{{ $tugasAkhir->id }}">Tolak</button>
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

        <!-- Modal Approve -->
        @foreach (Auth::user()->dosen->tugasAkhirs as $tugasAkhir)
            <div class="modal fade" id="approveModal{{ $tugasAkhir->id }}" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel{{ $tugasAkhir->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="approveModalLabel{{ $tugasAkhir->id }}">Setujui Tugas Akhir</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="{{ route('tugas-akhir.approve', $tugasAkhir) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menyetujui tugas akhir "<strong>{{ $tugasAkhir->judul }}</strong>"?</p>
                                <div class="form-group">
                                    <label for="catatan">Catatan (opsional)</label>
                                    <textarea name="catatan" id="catatan" class="form-control" rows="4">{{ old('catatan', $tugasAkhir->catatan) }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Setujui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal Reject -->
        @foreach (Auth::user()->dosen->tugasAkhirs as $tugasAkhir)
            <div class="modal fade" id="rejectModal{{ $tugasAkhir->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel{{ $tugasAkhir->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel{{ $tugasAkhir->id }}">Tolak Tugas Akhir</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="{{ route('tugas-akhir.reject', $tugasAkhir) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menolak tugas akhir "<strong>{{ $tugasAkhir->judul }}</strong>"?</p>
                                <div class="form-group">
                                    <label for="catatan">Catatan (opsional)</label>
                                    <textarea name="catatan" id="catatan" class="form-control" rows="4">{{ old('catatan', $tugasAkhir->catatan) }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
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