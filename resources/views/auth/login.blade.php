@extends('layouts.auth')

@section('title', 'Login')

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
                    <img src="{{ asset('images/logo.png') }}" alt="Thesistrack Logo" class="img-fluid" style="max-width: 250px; height: auto;">
                </div>
                
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Session Error -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Password">
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" style="cursor: pointer;">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>

                    <!-- Submit -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary py-2">Login</button>
                    </div>

                    <!-- Forgot Password and Register Links -->
                    <div class="text-center">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="btn btn-link text-primary text-decoration-none d-block mb-2">Forgot Your Password?</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-link text-primary text-decoration-none">Don't have an account? Register</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .btn-link.text-primary:hover {
        color: #0056b3 !important; /* Warna lebih gelap saat hover */
        text-decoration: underline !important; /* Garis bawah saat hover */
    }
    .card {
        overflow: hidden; /* Pastikan elemen di dalam card tidak mengganggu tata letak */
    }
</style>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Debugging form submission
            $('#login-form').on('submit', function (e) {
                const formData = new FormData(this);
                console.log('Login form submitted with data:', Object.fromEntries(formData));
            });

            // Toggle password visibility
            $('.toggle-password').on('click', function () {
                const passwordField = $('#password');
                const toggleIcon = $('#togglePasswordIcon');
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
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
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{!! session('status') !!}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@endsection