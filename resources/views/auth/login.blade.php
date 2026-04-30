@extends('layouts.auth')

@section('title', 'Login KGB Online')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Login</h2>
    <p class="text-sm text-gray-500 mt-2">
        Masukkan <span class="font-semibold">Email admin</span> beserta password.
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
            {{ session('pesan_blokir_login_skp', $errors->first('login')) ?: 'Anda tidak dapat masuk karena hasil penilaian kinerja (SKP) pada 1 (satu) tahun terakhir berada pada kategori yang memblokir akses, sesuai data penilaian kinerja yang tercatat.' }}
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
        <label for="login" class="block text-sm font-semibold text-gray-700 mb-2">Email Admin</label>
        <input
            id="login"
            type="text"
            name="login"
            value="{{ old('login') }}"
            required
            autofocus
            autocomplete="username"
            class="auth-input w-full border border-gray-300 rounded-xl px-4 py-3"
            placeholder="Masukkan email admin"
        >
    </div>

    <div>
        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
        <div class="relative">
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="auth-input w-full border border-gray-300 rounded-xl px-4 py-3 pr-12"
                placeholder="Masukkan password"
            >
            <button
                type="button"
                id="toggle-password"
                class="absolute inset-y-0 right-0 flex items-center justify-center px-4 text-gray-500 hover:text-gray-700"
                aria-label="Lihat password"
            >
                <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <label class="inline-flex items-center">
            <input
                type="checkbox"
                name="remember"
                class="rounded border-gray-300 text-blue-500 focus:ring-blue-500"
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

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('password');
        const btn = document.getElementById('toggle-password');
        if (!input || !btn) return;

        btn.addEventListener('click', function () {
            const isHidden = input.getAttribute('type') === 'password';
            input.setAttribute('type', isHidden ? 'text' : 'password');
            btn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Lihat password');
        });
    })();
</script>
@endpush