@extends('layouts.auth-pegawai')

@section('title', 'Lupa Password Pegawai KGB')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Lupa Password</h2>
    <p class="text-sm text-gray-500 mt-2">
        Masukkan email akun pegawai Anda. Kami akan mengirimkan link reset password ke email tersebut.
    </p>
</div>

@if (session('status'))
    <div class="mb-4 rounded-xl bg-green-100 border border-green-200 px-4 py-3 text-sm text-green-700">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-xl bg-red-100 border border-red-200 px-4 py-3 text-sm text-red-700">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('pegawai.password.email') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            autocomplete="username"
            class="auth-input w-full border border-gray-300 rounded-xl px-4 py-3"
            placeholder="Masukkan email pegawai"
        >
    </div>

    <button
        type="submit"
        class="auth-btn w-full text-white font-semibold py-3 rounded-xl"
    >
        Kirim Link Reset Password
    </button>
</form>

<div class="text-center mt-4">
    <a href="{{ route('pegawai.login') }}" class="text-sm text-gray-500 hover:text-orange-600">
        Kembali ke login
    </a>
</div>
@endsection

