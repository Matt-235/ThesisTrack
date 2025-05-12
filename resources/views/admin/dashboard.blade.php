@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard Admin</h1>
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
                        <p>Ini adalah dashboard untuk admin. Anda dapat mengelola mahasiswa dan dosen.</p>

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

                        <!-- Daftar Mahasiswa -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Mahasiswa</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createMahasiswaModal">Tambah Mahasiswa</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="mahasiswaTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>NIM</th>
                                            <th>Angkatan</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mahasiswas as $index => $mahasiswa)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $mahasiswa->user->nama ?? 'Nama tidak tersedia' }}</td>
                                                <td>{{ $mahasiswa->nim }}</td>
                                                <td>{{ $mahasiswa->angkatan }}</td>
                                                <td>{{ $mahasiswa->user->email }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm edit-mahasiswa" data-id="{{ $mahasiswa->id }}">Edit</button>
                                                    <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Daftar Dosen -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Dosen</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createDosenModal">Tambah Dosen</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="dosenTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>NIP</th>
                                            <th>Bidang Keahlian</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dosens as $index => $dosen)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $dosen->user->nama ?? 'Nama tidak tersedia' }}</td>
                                                <td>{{ $dosen->nip }}</td>
                                                <td>{{ $dosen->bidang_keahlian }}</td>
                                                <td>{{ $dosen->user->email }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm edit-dosen" data-id="{{ $dosen->id }}">Edit</button>
                                                    <form action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus dosen ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
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

        <!-- Modal Tambah Mahasiswa -->
        <div class="modal fade" id="createMahasiswaModal" tabindex="-1" role="dialog" aria-labelledby="createMahasiswaModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMahasiswaModalLabel">Tambah Mahasiswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nim">NIM</label>
                                <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}">
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="angkatan">Angkatan</label>
                                <input type="text" name="angkatan" id="angkatan" class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan') }}">
                                @error('angkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Mahasiswa -->
        <div class="modal fade" id="editMahasiswaModal" tabindex="-1" role="dialog" aria-labelledby="editMahasiswaModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMahasiswaModalLabel">Edit Mahasiswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="editMahasiswaForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_mahasiswa_id">
                            <div class="form-group">
                                <label for="edit_nama">Nama</label>
                                <input type="text" name="nama" id="edit_nama" class="form-control @error('nama') is-invalid @enderror">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit_password">Password (kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" id="edit_password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit_nim">NIM</label>
                                <input type="text" name="nim" id="edit_nim" class="form-control @error('nim') is-invalid @enderror">
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit_angkatan">Angkatan</label>
                                <input type="text" name="angkatan" id="edit_angkatan" class="form-control @error('angkatan') is-invalid @enderror">
                                @error('angkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Dosen -->
        <div class="modal fade" id="createDosenModal" tabindex="-1" role="dialog" aria-labelledby="createDosenModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createDosenModalLabel">Tambah Dosen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.dosen.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama_dosen">Nama</label>
                                <input type="text" name="nama" id="nama_dosen" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email_dosen">Email</label>
                                <input type="email" name="email" id="email_dosen" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_dosen">Password</label>
                                <input type="password" name="password" id="password_dosen" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}">
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bidang_keahlian">Bidang Keahlian</label>
                                <input type="text" name="bidang_keahlian" id="bidang_keahlian" class="form-control @error('bidang_keahlian') is-invalid @enderror" value="{{ old('bidang_keahlian') }}">
                                @error('bidang_keahlian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Dosen -->
        <div class="modal fade" id="editDosenModal" tabindex="-1" role="dialog" aria-labelledby="editDosenModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDosenModalLabel">Edit Dosen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="editDosenForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_dosen_id">
                            <div class="form-group">
                                <label for="edit_nama_dosen">Nama</label>
                                <input type="text" name="nama" id="edit_nama_dosen" class="form-control @error('nama') is-invalid @enderror">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit_email_dosen">Email</label>
                                <input type="email" name="email" id="edit_email_dosen" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit_password_dosen">Password (kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" id="edit_password_dosen" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit_nip">NIP</label>
                                <input type="text" name="nip" id="edit_nip" class="form-control @error('nip') is-invalid @enderror">
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit_bidang_keahlian">Bidang Keahlian</label>
                                <input type="text" name="bidang_keahlian" id="edit_bidang_keahlian" class="form-control @error('bidang_keahlian') is-invalid @enderror">
                                @error('bidang_keahlian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            // Inisialisasi DataTables
            $("#mahasiswaTable").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                searching: true,
                ordering: true,
                paging: true
            });

            $("#dosenTable").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                searching: true,
                ordering: true,
                paging: true
            });

            // Handle Edit Mahasiswa
            $('.edit-mahasiswa').on('click', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: '/admin/mahasiswa/' + id + '/edit',
                    method: 'GET',
                    success: function (data) {
                        $('#edit_mahasiswa_id').val(data.id);
                        $('#edit_nama').val(data.nama);
                        $('#edit_email').val(data.email);
                        $('#edit_nim').val(data.nim);
                        $('#edit_angkatan').val(data.angkatan);
                        $('#editMahasiswaForm').attr('action', '/admin/mahasiswa/' + data.id);
                        $('#editMahasiswaModal').modal('show');
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal memuat data mahasiswa.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Handle Edit Dosen
            $('.edit-dosen').on('click', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: '/admin/dosen/' + id + '/edit',
                    method: 'GET',
                    success: function (data) {
                        $('#edit_dosen_id').val(data.id);
                        $('#edit_nama_dosen').val(data.nama);
                        $('#edit_email_dosen').val(data.email);
                        $('#edit_nip').val(data.nip);
                        $('#edit_bidang_keahlian').val(data.bidang_keahlian);
                        $('#editDosenForm').attr('action', '/admin/dosen/' + data.id);
                        $('#editDosenModal').modal('show');
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal memuat data dosen.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Notifikasi
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{!! session('success') !!}',
                    confirmButtonText: 'OK'
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{!! session('error') !!}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@stop