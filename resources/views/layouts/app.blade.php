<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Thesistrack')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }
        .main-header {
            background: linear-gradient(90deg, #1e3a8a, #3b82f6);
            color: white;
            border-bottom: 2px solid #2563eb;
        }
        .main-header .navbar-nav .nav-link {
            color: white;
            transition: all 0.3s;
        }
        .main-header .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        .main-sidebar {
            background-color: #1e293b;
        }
        .brand-link {
            background-color: #111827;
            color: white !important;
            text-align: center;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .nav-sidebar .nav-link {
            color: #d1d5db;
            border-radius: 5px;
            margin: 5px 10px;
            transition: all 0.3s;
        }
        .nav-sidebar .nav-link:hover {
            background-color: #2563eb;
            color: white;
        }
        .nav-sidebar .nav-icon {
            margin-right: 10px;
        }
        .nav-sidebar .nav-item.active .nav-link {
            background-color: #2563eb;
            color: white;
            font-weight: 600;
            border-left: 4px solid #3b82f6;
        }
        .content-wrapper {
            background-color: #f4f6f9;
        }
        .main-footer {
            background-color: #1e3a8a;
            color: white;
            border-top: 2px solid #2563eb;
        }
        .btn-home {
            background-color: #10b981;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .btn-home:hover {
            background-color: #059669;
            color: white;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item:hover {
            background-color: #2563eb;
            color: white;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-home" href="{{ url('/') }}"><i class="fas fa-home"></i> Back to Home</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-user"></i>
                            {{ Auth::user() && Auth::user()->nama ? Auth::user()->nama : 'Pengguna' }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ url('/') }}" class="brand-link">
                <span class="brand-text font-weight-light">Thesistrack</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        @if (Auth::check())
                            @if (Auth::user()->role === 'admin')
                                <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                            @elseif (Auth::user()->role === 'dosen')
                                <li class="nav-item {{ Route::is('dosen.dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('dosen.dashboard') }}" class="nav-link">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                            @elseif (Auth::user()->role === 'mahasiswa')
                                <li class="nav-item {{ Route::is('mahasiswa.dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item {{ Route::is('tugas-akhir.index') ? 'active' : '' }}">
                                <a href="{{ route('tugas-akhir.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Tugas Akhir</p>
                                </a>
                            </li>
                            @if (Auth::user()->role === 'mahasiswa' || Auth::user()->role === 'dosen')
                                <li class="nav-item {{ Route::is('bimbingan.index') ? 'active' : '' }}">
                                    <a href="{{ route('bimbingan.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-sticky-note"></i>
                                        <p>Bimbingan</p>
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            @yield('content_header')
                        </div>
                    </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Inisialisasi AdminLTE PushMenu
            $('[data-widget="pushmenu"]').PushMenu({
                autoCollapseSize: 768
            });

            // Debugging
            console.log('jQuery version:', $.fn.jquery);
            console.log('AdminLTE loaded:', typeof $.fn.PushMenu !== 'undefined');
            $('[data-widget="pushmenu"]').on('click', function () {
                console.log('Pushmenu clicked');
            });
        });
    </script>
    @yield('js')
</body>
</html>     