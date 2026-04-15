@extends('layouts.public')

@section('title', 'Cek Registrasi')

@section('content')
<section class="py-14">
    <div class="max-w-5xl mx-auto px-4 space-y-8">
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h1 class="text-3xl font-bold mb-4">Cek Registrasi Dinonaktifkan</h1>
            <p class="text-gray-700 leading-relaxed">
                Saat ini fitur cek registrasi belum ditampilkan untuk publik.
                Silakan lihat <span class="font-semibold">SK Kenaikan Gaji Berkala Terbaru</span> berikut ini.
            </p>

            <div class="mt-6">
                <a href="{{ route('public.sk.index') }}" class="btn-primary inline-block text-white px-8 py-3 rounded-lg font-semibold">
                    Lihat SK Terbaru
                </a>
            </div>
        </div>
    </div>
</section>
@endsection