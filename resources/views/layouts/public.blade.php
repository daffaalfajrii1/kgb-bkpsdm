<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KGB Online')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-rejang-lebong.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #E74C3C;
            --secondary: #E67E22;
            --dark: #1f2937;
            --light: #f9fafb;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, var(--primary), var(--secondary)); }
        .btn-primary { background: linear-gradient(to right, var(--primary), var(--secondary)); transition: .3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,.08); }
        .service-card { transition: .3s ease; border-left: 4px solid var(--primary); }
        .service-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,.08); }
    </style>
</head>
<body class="bg-gray-50">
<header class="gradient-bg text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 md:py-4 flex justify-between items-center gap-3">
        <a href="{{ route('home') }}" class="flex items-center gap-3 min-w-0">
    <img src="{{ asset('assets/img/logo-rejang-lebong.png') }}" alt="Logo Rejang Lebong" class="w-12 h-12 object-contain bg-white rounded-full p-1">
    <div class="min-w-0">
        <div class="font-bold text-base sm:text-lg md:text-xl truncate">Kenaikan Gaji Berkala ONLINE</div>
        <div class="text-xs opacity-90">Kabupaten Rejang Lebong</div>
    </div>
</a>

        <nav class="hidden md:flex gap-6 text-sm font-medium">
            <a href="{{ route('home') }}">Beranda</a>

            @php
                $pegawaiLoggedIn = Auth::guard('pegawai')->check();
            @endphp

            {{-- Pengajuan KGB: jika sudah login pegawai langsung ke form, jika belum ke halaman login pegawai --}}
            @if ($pegawaiLoggedIn)
                <a href="{{ route('pegawai.pengajuan.create') }}">Pengajuan KGB</a>
            @else
                <a href="{{ route('pegawai.login') }}">Pengajuan KGB</a>
            @endif

            {{-- Tombol login / dashboard hanya untuk pegawai di area publik --}}
            @if ($pegawaiLoggedIn)
                <a href="{{ route('pegawai.dashboard') }}">Dashboard Pegawai</a>
                <a href="{{ route('pegawai.profile.show') }}">Profil Pegawai</a>
            @else
                <a href="{{ route('pegawai.login') }}">Login Pegawai</a>
            @endif
            <a href="{{ url('/admin/login') }}">Login Admin</a>
        </nav>

        <button
            type="button"
            class="md:hidden inline-flex items-center justify-center rounded-lg border border-white/30 p-2 hover:bg-white/10"
            data-mobile-menu-button
            aria-controls="mobile-menu"
            aria-expanded="false"
            aria-label="Buka menu navigasi"
        >
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div id="mobile-menu" class="md:hidden hidden border-t border-white/20 px-4 py-3">
        <nav class="flex flex-col gap-3 text-sm font-medium">
            <a href="{{ route('home') }}">Beranda</a>

            @if ($pegawaiLoggedIn)
                <a href="{{ route('pegawai.pengajuan.create') }}">Pengajuan KGB</a>
                <a href="{{ route('pegawai.dashboard') }}">Dashboard Pegawai</a>
                <a href="{{ route('pegawai.profile.show') }}">Profil Pegawai</a>
            @else
                <a href="{{ route('pegawai.login') }}">Pengajuan KGB</a>
                <a href="{{ route('pegawai.login') }}">Login Pegawai</a>
            @endif
            <a href="{{ url('/admin/login') }}">Login Admin</a>
        </nav>
    </div>
</header>

<main>
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 pt-6">
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-7xl mx-auto px-4 pt-6">
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="bg-gray-900 text-gray-200 mt-16">
    <div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-8">
        <div>
            <h3 class="font-semibold text-lg mb-3">KGB Online</h3>
            <p class="text-sm text-gray-300">Layanan pengajuan, pelacakan registrasi, dan unduh SK Kenaikan Gaji Berkala secara online.</p>
        </div>
        <div>
            <h3 class="font-semibold text-lg mb-3">Menu</h3>
            <ul class="space-y-2 text-sm">
                @if ($pegawaiLoggedIn ?? false)
                    <li><a href="{{ route('pegawai.pengajuan.create') }}">Pengajuan KGB</a></li>
                    <li><a href="{{ route('pegawai.dashboard') }}">Dashboard Pegawai</a></li>
                    <li><a href="{{ route('pegawai.profile.show') }}">Profil Pegawai</a></li>
                @else
                    <li><a href="{{ route('pegawai.login') }}">Pengajuan KGB</a></li>
                    <li><a href="{{ route('pegawai.login') }}">Login Pegawai</a></li>
                @endif
            </ul>
        </div>
        <div>
            <h3 class="font-semibold text-lg mb-3">Kontak</h3>
            <p class="text-sm text-gray-300">BKPSDM</p>
            <p class="text-sm text-gray-300">Jam layanan: Senin - Jumat</p>
        </div>
    </div>
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-4 text-center text-xs sm:text-sm text-gray-400">
            &copy; {{ date('Y') }} Diskominfo Rejang Lebong. All rights reserved.
        </div>
    </div>
</footer>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.querySelector('[data-mobile-menu-button]');
            const menu = document.getElementById('mobile-menu');

            if (!button || !menu) {
                return;
            }

            button.addEventListener('click', function () {
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                button.setAttribute('aria-expanded', String(!isExpanded));
                menu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>