@extends('layouts.app')

@section('title', 'Tugas Akhir')

@section('content_header')
    <h1>Tugas Akhir</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Tugas Akhir</h3>
            <div class="float-right">
                @if (Auth::user()->role === 'mahasiswa')
                    <a href="{{ route('tugas-akhir.create') }}" class="btn btn-primary btn-sm">Tambah Tugas Akhir</a>
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
            <table id="tugasAkhirTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Mahasiswa</th>
                        <th>Dosen</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tugasAkhirs as $index => $tugasAkhir)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $tugasAkhir->judul }}</td>
                            <td>{{ $tugasAkhir->mahasiswa->user->nama }}</td>
                            <td>{{ $tugasAkhir->dosen ? $tugasAkhir->dosen->user->nama : '-' }}</td>
                            <td>{{ ucfirst($tugasAkhir->status) }}</td>
                            <td>{{ $tugasAkhir->catatan ?? '-' }}</td>
                            <td>
                                <a href="{{ route('tugas-akhir.show', $tugasAkhir) }}" class="btn btn-info btn-sm">Detail</a>
                                @if (Auth::user()->role === 'mahasiswa' && $tugasAkhir->mahasiswa_id === Auth::user()->mahasiswa->id && $tugasAkhir->status === 'pending')
                                    <a href="{{ route('tugas-akhir.edit', $tugasAkhir) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('tugas-akhir.destroy', $tugasAkhir) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tugas akhir ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                                @if (Auth::user()->role === 'dosen' && $tugasAkhir->dosen_id === Auth::user()->dosen->id && $tugasAkhir->status === 'pending')
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

    <!-- Modal Approve -->
    @foreach ($tugasAkhirs as $tugasAkhir)
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
    @foreach ($tugasAkhirs as $tugasAkhir)
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