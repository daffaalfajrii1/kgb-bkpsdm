@extends('layouts.auth')

@section('title', 'Login Pegawai KGB')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Login Pegawai</h2>
    <p class="text-sm text-gray-500 mt-2">
        Masukkan NIP dan password untuk mengajukan Kenaikan Gaji Berkala.
    </p>
</div>

@if (session('status'))
    <div class="mb-4 rounded-xl bg-green-100 border border-green-200 px-4 py-3 text-sm text-green-700">
        {{ session('status') }}
    </div>
@endif

@if (session('login_blokir_skp'))
    <div role="alert" class="mb-4 rounded-xl border-l-4 border-amber-500 bg-amber-50 text-amber-950 px-4 py-4 text-sm shadow-sm">
        <p class="font-bold text-amber-900 mb-2">Tidak dapat masuk ke sistem</p>
        <p class="leading-relaxed text-amber-900/95">
            {{ session('pesan_blokir_login_skp', $errors->first('nip')) ?: 'Anda tidak dapat masuk karena hasil penilaian kinerja (SKP) pada 2 (dua) tahun terakhir berada pada kategori Buruk atau Sangat Buruk, sesuai data penilaian kinerja yang tercatat.' }}
        </p>
    </div>
@elseif ($errors->any())
    <div class="mb-4 rounded-xl bg-red-100 border border-red-200 px-4 py-3 text-sm text-red-700">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('pegawai.login') }}" class="space-y-5">
    @csrf

    <div>
        <label for="nip" class="block text-sm font-semibold text-gray-700 mb-2">NIP</label>
        <input
            id="nip"
            type="text"
            name="nip"
            value="{{ old('nip') }}"
            required
            autofocus
            autocomplete="username"
            class="auth-input w-full border border-gray-300 rounded-xl px-4 py-3"
            placeholder="Masukkan NIP"
        >
    </div>

    <div>
        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
        <input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            class="auth-input w-full border border-gray-300 rounded-xl px-4 py-3"
            placeholder="Masukkan password"
        >
    </div>

    <div class="flex items-center justify-between">
        <label class="inline-flex items-center">
            <input
                type="checkbox"
                name="remember"
                class="rounded border-gray-300 text-orange-500 focus:ring-orange-500"
            >
            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
        </label>

        <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-orange-600">
            Kembali ke beranda
        </a>
    </div>

    <button
        type="submit"
        class="auth-btn w-full text-white font-semibold py-3 rounded-xl"
    >
        Masuk
    </button>
</form>
@endsection

