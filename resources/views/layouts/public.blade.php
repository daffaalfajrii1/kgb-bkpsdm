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
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
    <img src="{{ asset('assets/img/logo-rejang-lebong.png') }}" alt="Logo Rejang Lebong" class="w-12 h-12 object-contain bg-white rounded-full p-1">
    <div>
        <div class="font-bold text-xl">Kenaikan Gaji Berkala ONLINE</div>
        <div class="text-xs opacity-90">Kabupaten Rejang Lebong</div>
    </div>
</a>

        <nav class="hidden md:flex gap-6 text-sm font-medium">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('public.pengajuan.create') }}">Pengajuan KGB</a>
            <a href="{{ route('public.status.index') }}">Cek Registrasi</a>
            <a href="{{ route('public.sk.index') }}">SK KGB</a>
            <a href="{{ route('login') }}">Admin</a>
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
                <li><a href="{{ route('public.pengajuan.create') }}">Pengajuan KGB</a></li>
                <li><a href="{{ route('public.status.index') }}">Cek Registrasi</a></li>
                <li><a href="{{ route('public.sk.index') }}">SK KGB</a></li>
            </ul>
        </div>
        <div>
            <h3 class="font-semibold text-lg mb-3">Kontak</h3>
            <p class="text-sm text-gray-300">BKPSDM</p>
            <p class="text-sm text-gray-300">Jam layanan: Senin - Jumat</p>
        </div>
    </div>
</footer>
</body>
</html>