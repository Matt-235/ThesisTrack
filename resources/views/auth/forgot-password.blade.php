@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

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
                <h3 class="text-center mb-4 custom-title">Lupa Kata Sandi</h3>
                
                <!-- Teks Informasi -->
                <div class="text-center mb-4 text-sm text-gray-600">
                    Lupa kata sandi? Masukkan email Anda untuk menerima link reset.
                </div>

                <!-- Session Status -->
                @if (session()->has('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Session Error -->
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form">
                    @csrf

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') ?? '' }}" required autofocus placeholder="Email">
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->get('email')[0] }}</div>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary py-2">Kirim Link Reset</button>
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
    .text-sm {
        font-size: 0.875rem;
    }
    .text-gray-600 {
        color: #6b7280;
    }
</style>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Debugging form submission
            $('#forgot-password-form').on('submit', function (e) {
                const formData = new FormData(this);
                console.log('Forgot password form submitted with data:', Object.fromEntries(formData));
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
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@endsection