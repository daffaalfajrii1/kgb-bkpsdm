<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin KGB')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-rejang-lebong.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-flex align-items-center mr-3">
                <span>{{ auth()->user()->name }}</span>
            </li>
            <li class="nav-item mr-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex align-items-center">
    <img src="{{ asset('assets/img/logo-rejang-lebong.png') }}"
         alt="Logo Rejang Lebong"
         class="brand-image img-circle elevation-3"
         style="opacity: .95; object-fit: contain; background: white; padding: 2px;">
    <span class="brand-text font-weight-light">Admin KGB RL</span>
</a>

        <div class="sidebar">
            <nav class="mt-3">
                <ul class="nav nav-pills nav-sidebar flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-house"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pengajuan.index') }}" class="nav-link {{ request()->routeIs('admin.pengajuan.index') || request()->routeIs('admin.pengajuan.show') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-lines"></i>
                            <p>Data Pengajuan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pengajuan.diproses') }}" class="nav-link {{ request()->routeIs('admin.pengajuan.diproses') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-spinner"></i>
                            <p>Data Diproses</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.hasil-kgb.index') }}" class="nav-link {{ request()->routeIs('admin.hasil-kgb.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-pdf"></i>
                            <p>Upload Hasil KGB</p>
                        </a>
                    </li>

                    <li class="nav-item">
    <a href="{{ route('admin.pengajuan.selesai.index') }}" class="nav-link {{ request()->routeIs('admin.pengajuan.selesai.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-circle-check"></i>
        <p>Pengajuan Selesai</p>
    </a>
</li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>@yield('page_title', 'Dashboard')</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-sm">
        <strong>KGB BKPSDM</strong> - Sistem Kenaikan Gaji Berkala
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>