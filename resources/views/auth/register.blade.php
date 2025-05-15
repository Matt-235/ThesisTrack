@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4 position-relative">
                <!-- Tombol Silang -->
                <a href="/" class="position-absolute top-0 end-0 m-3 text-dark text-decoration-none" title="Kembali ke Beranda">
                    <i class="fas fa-times fa-lg"></i>
                </a>
                
                <h3 class="text-center mb-4 custom-title">Daftar Akun</h3>
                <form method="POST" action="{{ route('register') }}" id="register-form">
                    @csrf
                    <!-- Nama -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required autofocus placeholder="Nama">
                        <label for="nama"><i class="fas fa-user me-2"></i>Nama</label>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- NIM (untuk mahasiswa) -->
                    <div class="form-floating mb-3" id="nim-group" style="display: none;">
                        <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim') }}" placeholder="NIM">
                        <label for="nim"><i class="fas fa-id-card me-2"></i>NIM</label>
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Angkatan (untuk mahasiswa) -->
                    <div class="form-floating mb-3" id="angkatan-group" style="display: none;">
                        <input type="number" class="form-control @error('angkatan') is-invalid @enderror" id="angkatan" name="angkatan" value="{{ old('angkatan') }}" placeholder="Angkatan" min="2018" max="2030" step="1">
                        <label for="angkatan"><i class="fas fa-calendar me-2"></i>Angkatan</label>
                        @error('angkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- NIP (untuk dosen) -->
                    <div class="form-floating mb-3" id="nip-group" style="display: none;">
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}" placeholder="NIP">
                        <label for="nip"><i class="fas fa-id-badge me-2"></i>NIP</label>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Bidang Keahlian (untuk dosen) -->
                    <div class="form-floating mb-3" id="bidang-keahlian-group" style="display: none;">
                        <input type="text" class="form-control @error('bidang_keahlian') is-invalid @enderror" id="bidang_keahlian" name="bidang_keahlian" value="{{ old('bidang_keahlian') }}" placeholder="Bidang Keahlian">
                        <label for="bidang_keahlian"><i class="fas fa-briefcase me-2"></i>Bidang Keahlian</label>
                        @error('bidang_keahlian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="Email">
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Password -->
                    <div class="form-floating mb-3 position-relative">
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Password">
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" style="cursor: pointer;" data-target="password">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Konfirmasi Password -->
                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi Password">
                        <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label>
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" style="cursor: pointer;" data-target="password_confirmation">
                            <i class="fas fa-eye" id="togglePasswordConfirmationIcon"></i>
                        </span>
                    </div>
                    <!-- Role -->
                    <div class="form-floating mb-3">
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        </select>
                        <label for="role"><i class="fas fa-user-tag me-2"></i>Role</label>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Submit -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary py-2">Register</button>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">Sudah punya akun? Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .custom-title {
        color: #003087; /* Warna biru tua untuk tema profesional */
        font-size: 2rem; /* Ukuran font lebih besar */
        font-weight: 700; /* Tebal untuk kesan kuat */
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1); /* Bayangan teks untuk efek modern */
    }
</style>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Kontrol tampilan field berdasarkan role
            function toggleFields() {
                const role = $('#role').val();
                $('#nim-group, #angkatan-group, #nip-group, #bidang-keahlian-group').hide();
                $('#nim, #angkatan, #nip, #bidang_keahlian').prop('required', false);
                if (role === 'mahasiswa') {
                    $('#nim-group, #angkatan-group').show();
                    $('#nim, #angkatan').prop('required', true);
                } else if (role === 'dosen') {
                    $('#nip-group, #bidang-keahlian-group').show();
                    $('#nip, #bidang_keahlian').prop('required', true);
                }
            }

            // Panggil saat halaman dimuat
            toggleFields();

            // Panggil saat role berubah
            $('#role').on('change', toggleFields);

            // Validasi input angkatan hanya angka
            $('#angkatan').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Toggle password visibility
            $('.toggle-password').on('click', function () {
                const targetId = $(this).data('target');
                const passwordField = $('#' + targetId);
                const toggleIcon = $(this).find('i');
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Debugging form submission
            $('#register-form').on('submit', function (e) {
                const formData = new FormData(this);
                console.log('Register form submitted with data:', Object.fromEntries(formData));
            });

            // Tampilkan pesan error dari session
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{!! session('error') !!}',
                    confirmButtonText: 'OK'
                });
            @endif

            // Tampilkan pesan sukses dari session
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{!! session('success') !!}',
                    confirmButtonText: 'OK',
                    willClose: () => {
                        window.location.href = '{{ route('login') }}';
                    }
                });
            @endif
        });
    </script>
@endsection