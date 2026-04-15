<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login Pegawai KGB')</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-rejang-lebong.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --primary: #e74c3c;
            --secondary: #e67e22;
        }

        .auth-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .auth-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transition: all 0.25s ease;
        }

        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(231, 76, 60, 0.22);
        }

        .auth-input:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.18);
        }
    </style>
</head>
<body class="min-h-screen auth-gradient flex items-center justify-center px-4 py-8">
<div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">
    <div class="hidden lg:flex auth-gradient text-white p-12 flex-col justify-center">
        <div class="flex items-center gap-4 mb-8">
            <img
                src="{{ asset('assets/img/logo-rejang-lebong.png') }}"
                alt="Logo Rejang Lebong"
                class="w-20 h-20 object-contain rounded-full bg-white p-2 shadow-lg"
            >
            <div>
                <h1 class="text-3xl font-bold">KGB Online</h1>
                <p class="text-sm text-orange-100">Kabupaten Rejang Lebong</p>
            </div>
        </div>

        <h2 class="text-4xl font-bold leading-tight mb-5">
            Halaman Login Pegawai
        </h2>

        <p class="text-orange-50 leading-7 text-sm max-w-lg">
            Gunakan akun pegawai untuk mengajukan Kenaikan Gaji Berkala, memantau status
            pengajuan, dan mengunduh dokumen yang telah selesai.
        </p>

        <div class="mt-10 grid grid-cols-2 gap-4 max-w-md">
            <div class="bg-white/10 rounded-2xl p-4">
                <div class="text-2xl mb-2"><i class="fas fa-file-circle-plus"></i></div>
                <div class="font-semibold">Pengajuan</div>
            </div>
            <div class="bg-white/10 rounded-2xl p-4">
                <div class="text-2xl mb-2"><i class="fas fa-magnifying-glass"></i></div>
                <div class="font-semibold">Cek Status</div>
            </div>
            <div class="bg-white/10 rounded-2xl p-4">
                <div class="text-2xl mb-2"><i class="fas fa-file-pdf"></i></div>
                <div class="font-semibold">Unduh SK</div>
            </div>
            <div class="bg-white/10 rounded-2xl p-4">
                <div class="text-2xl mb-2"><i class="fas fa-arrow-right-arrow-left"></i></div>
                <div class="font-semibold">Riwayat</div>
            </div>
        </div>
    </div>

    <div class="bg-white flex items-center justify-center p-8 md:p-12">
        <div class="w-full max-w-md">
            <div class="lg:hidden flex flex-col items-center text-center mb-8">
                <img
                    src="{{ asset('assets/img/logo-rejang-lebong.png') }}"
                    alt="Logo Rejang Lebong"
                    class="w-24 h-24 object-contain rounded-full bg-white p-2 shadow mb-4"
                >
                <h1 class="text-2xl font-bold text-gray-800">Pegawai KGB</h1>
                <p class="text-sm text-gray-500">Kabupaten Rejang Lebong</p>
            </div>

            @yield('content')
        </div>
    </div>
</div>
</body>
</html>

