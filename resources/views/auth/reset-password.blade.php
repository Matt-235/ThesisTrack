@extends('layouts.auth')

@section('title', 'Reset Kata Sandi')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4 position-relative">
                <!-- Tombol Silang -->
                <a href="/" class="position-absolute top-0 end-0 m-3 text-dark text-decoration-none" title="Kembali ke Beranda">
                    <i class="fas fa-times fa-lg"></i>
                </a>
                
                <!-- Logo -->
                <div class="d-flex justify-content-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Thesistrack Logo" class="img-fluid" style="max-width: 200px; height: auto;">
                </div>
                
                <!-- Judul -->
                <h3 class="text-center mb-4 custom-title">Reset Kata Sandi</h3>

                <!-- Session Error -->
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}" id="reset-password-form">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $request->email) ?? '' }}" required autofocus placeholder="Email" autocomplete="username">
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->get('email')[0] }}</div>
                        @endif
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Password" autocomplete="new-password">
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" style="cursor: pointer;" data-target="password">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->get('password')[0] }}</div>
                        @endif
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi Password" autocomplete="new-password">
                        <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label>
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" style="cursor: pointer;" data-target="password_confirmation">
                            <i class="fas fa-eye" id="togglePasswordConfirmationIcon"></i>
                        </span>
                        @if ($errors->has('password_confirmation'))
                            <div class="invalid-feedback">{{ $errors->get('password_confirmation')[0] }}</div>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary py-2">Simpan Kata Sandi Baru</button>
                    </div>

                    <!-- Kembali ke Login -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="btn btn-link text-primary text-decoration-none">Kembali ke Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .custom-title {
        color: #003087;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }
    .btn-link.text-primary:hover {
        color: #0056b3 !important;
        text-decoration: underline !important;
    }
    .card {
        overflow: hidden;
    }
</style>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Debugging form submission
            $('#reset-password-form').on('submit', function (e) {
                const formData = new FormData(this);
                console.log('Reset password form submitted with data:', Object.fromEntries(formData));
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

            // Tampilkan pesan error dari session
            @if (session()->has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{!! session('error') !!}',
                    confirmButtonText: 'OK'
                });
            @endif

            // Tampilkan pesan sukses dari session
            @if (session()->has('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{!! session('status') !!}',
                    confirmButtonText: 'OK',
                    willClose: () => {
                        window.location.href = '{{ route('login') }}';
                    }
                });
            @endif
        });
    </script>
@endsection