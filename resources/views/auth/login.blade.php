@extends('layouts.auth')

@section('title', 'Login KGB Online')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Login</h2>
    <p class="text-sm text-gray-500 mt-2">
        Masukkan <span class="font-semibold">Email (untuk admin)</span> atau <span class="font-semibold">NIP (untuk pegawai)</span> beserta password.
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
            {{ session('pesan_blokir_login_skp', $errors->first('login')) ?: 'Anda tidak dapat masuk karena hasil penilaian kinerja (SKP) pada 2 (dua) tahun terakhir berada pada kategori Buruk atau Sangat Buruk, sesuai data penilaian kinerja yang tercatat.' }}
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

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <div>
        <label for="login" class="block text-sm font-semibold text-gray-700 mb-2">Email / NIP</label>
        <input
            id="login"
            type="text"
            name="login"
            value="{{ old('login') }}"
            required
            autofocus
            autocomplete="username"
            class="auth-input w-full border border-gray-300 rounded-xl px-4 py-3"
            placeholder="Masukkan email admin atau NIP pegawai"
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

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-orange-600 hover:underline">
                Lupa password?
            </a>
        @endif
    </div>

    <button
        type="submit"
        class="auth-btn w-full text-white font-semibold py-3 rounded-xl"
    >
        Masuk
    </button>

    <div class="text-center">
        <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-orange-600">
            Kembali ke beranda
        </a>
    </div>
</form>
@endsection