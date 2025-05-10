<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Thesistrack</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <!-- Custom CSS -->
    <style>
        .logout-btn {
            position: relative;
            z-index: 1000;
            cursor: pointer;
            pointer-events: auto;
            padding: 5px 10px;
        }
        .navbar-nav.ml-auto .nav-item {
            margin-right: 10px;
        }
        .navbar-text {
            font-weight: 500;
        }
        .navbar-nav.ml-auto {
            position: relative;
            z-index: 1000;
        }
        #logout-form {
            display: inline-block;
        }
        .alert-js-error {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            padding: 15px;
            max-width: 300px;
        }
    </style>
    @stack('css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-flex align-items-center mr-2">
                <span class="navbar-text">{{ Auth::user()->nama }} ({{ Auth::user()->role }})</span>
            </li>
            <li class="nav-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="js" value="1">
                    <button type="submit" class="btn btn-danger btn-sm logout-btn" onclick="return confirm('Yakin ingin logout?');">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-link btn-sm ml-2">Logout (No JS)</a>
            </li>
        </ul>
    </nav>
    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('home') }}" class="brand-link">
            <span class="brand-text font-weight-light">Thesistrack</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    @if (Auth::user()->role === 'admin')
                        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                    @elseif (Auth::user()->role === 'dosen')
                        <li class="nav-item {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dosen.dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                    @else
                        <li class="nav-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item {{ request()->routeIs('tugas-akhir.*') ? 'active' : '' }}">
                        <a href="{{ route('tugas-akhir.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Tugas Akhir</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('bimbingan.*') ? 'active' : '' }}">
                        <a href="{{ route('bimbingan.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Bimbingan</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                @yield('content_header')
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>
    <!-- Footer -->
    <footer class="main-footer">
        <strong>Thesistrack © {{ date('Y') }}</strong>
    </footer>
</div>
<!-- Scripts -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
<script>
    // Cek pemuatan JavaScript
    console.log('JavaScript block loaded for role: {{ Auth::user()->role }} at ' + new Date().toISOString());
    try {
        if (typeof jQuery === 'undefined') {
            throw new Error('jQuery is not loaded');
        }
        console.log('jQuery is loaded, version:', jQuery.fn.jquery);
    } catch (e) {
        console.error(e.message);
        document.write('<div class="alert alert-danger alert-js-error">Error: jQuery gagal dimuat. Animasi atau fungsi mungkin terganggu. Gunakan link Logout (No JS).</div>');
    }

    // Debugging tombol logout
    try {
        var logoutButton = document.getElementById('logout-form');
        if (logoutButton) {
            console.log('Logout form found with ID: logout-form');
            logoutButton.addEventListener('submit', function() {
                console.log('Logout form submitted for role: {{ Auth::user()->role }} at ' + new Date().toISOString());
            });
        } else {
            console.error('Logout form not found with ID: logout-form');
        }
    } catch (e) {
        console.error('Error in logout script:', e.message);
    }
</script>
@stack('js')
</body>
</html>