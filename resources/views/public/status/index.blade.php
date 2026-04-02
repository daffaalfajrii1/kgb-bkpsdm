@extends('layouts.public')

@section('title', 'Cek Registrasi')

@section('content')
<section class="py-14">
    <div class="max-w-5xl mx-auto px-4 space-y-8">
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h1 class="text-3xl font-bold mb-4">Cek Status Registrasi</h1>
            <form action="{{ route('public.status.search') }}" method="GET" class="grid md:grid-cols-[1fr_auto] gap-4">
                <input type="text" name="keyword" value="{{ $prefill }}" class="border rounded-lg px-4 py-3" placeholder="Masukkan nomor registrasi / nama / NIP">
                <button class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">Cari</button>
            </form>
        </div>

        @if ($pengajuan)
            <div class="bg-white rounded-2xl shadow-md p-8">
                <h2 class="text-2xl font-semibold mb-4">Hasil Pencarian</h2>
                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div><span class="font-semibold">No Registrasi:</span> {{ $pengajuan->nomor_registrasi }}</div>
                    <div><span class="font-semibold">Nama:</span> {{ $pengajuan->nama }}</div>
                    <div><span class="font-semibold">NIP:</span> {{ $pengajuan->nip }}</div>
                    <div><span class="font-semibold">Instansi:</span> {{ $pengajuan->dinas_instansi }}</div>
                    <div><span class="font-semibold">Pangkat:</span> {{ $pengajuan->pangkat_terakhir }}</div>
                    <div><span class="font-semibold">Status:</span>
                        @if ($pengajuan->status === 'diajukan')
                            <span class="text-gray-700 font-semibold">Diajukan</span>
                        @elseif ($pengajuan->status === 'diproses')
                            <span class="text-yellow-600 font-semibold">Diproses</span>
                        @else
                            <span class="text-green-600 font-semibold">Selesai</span>
                        @endif
                    </div>
                </div>

                @if ($pengajuan->status === 'selesai' && $pengajuan->hasilKgb)
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <div class="font-semibold text-green-700 mb-2">SK Kenaikan Gaji Berkala sudah selesai.</div>
                        <a href="{{ route('public.sk.download', $pengajuan->hasilKgb->id) }}" class="btn-primary inline-block text-white px-6 py-3 rounded-lg font-semibold">
                            Unduh SK Kenaikan Gaji Berkala
                        </a>
                    </div>
                @endif
            </div>
        @elseif($prefill)
            <div class="bg-white rounded-2xl shadow-md p-8 text-gray-600">
                Data tidak ditemukan.
            </div>
        @endif
    </div>
</section>
@endsection