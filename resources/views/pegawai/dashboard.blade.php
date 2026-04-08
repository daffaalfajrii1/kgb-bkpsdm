@extends('layouts.public')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    onclick="window.history.back()"
                    class="inline-flex items-center px-3 py-2 rounded-lg border border-white/60 text-sm text-white/90 hover:bg-white/10 transition"
                >
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </button>
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Pegawai</h1>
                <p class="text-gray-600 mt-2">
                    Selamat datang,
                    <span class="font-semibold">
                        {{ trim(($user->gelar_depan ? $user->gelar_depan.' ' : '').$user->name.' '.($user->gelar_belakang ?? '')) }}
                    </span>
                    (NIP: {{ $user->nip ?? '-' }}).
                </p>
            </div>
            <div class="flex items-center gap-3 justify-end">
                <form method="POST" action="{{ route('pegawai.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100"
                    >
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-orange-500"><i class="fas fa-id-card"></i></span>
                    Profil Pegawai
                </h2>
                <div class="grid sm:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <div class="text-gray-500">Nama Lengkap</div>
                        <div class="font-medium">{{ $user->name }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">NIP</div>
                        <div class="font-medium">{{ $user->nip ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Email Aktif</div>
                        <div class="font-medium">{{ $user->email ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Dinas / Instansi</div>
                        <div class="font-medium">{{ $user->dinas_instansi ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Gol. / Pangkat</div>
                        <div class="font-medium">{{ $user->gol_pangkat ?? $user->pangkat_terakhir ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">TMT Golongan</div>
                        <div class="font-medium">{{ $user->tmt_golongan?->format('d/m/Y') ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Masa kerja</div>
                        <div class="font-medium">
                            @if ($user->mk_tahun !== null || $user->mk_bulan !== null)
                                {{ (int) ($user->mk_tahun ?? 0) }} th {{ (int) ($user->mk_bulan ?? 0) }} bln
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500">TMT Jabatan</div>
                        <div class="font-medium">{{ $user->tmt_jabatan?->format('d/m/Y') ?? '-' }}</div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="text-gray-500">Alamat</div>
                        <div class="font-medium">{{ $user->alamat ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-orange-500"><i class="fas fa-list-check"></i></span>
                    Menu Cepat
                </h2>
                <div class="space-y-3">
                    <a
                        href="{{ route('pegawai.pengajuan.create') }}"
                        class="w-full inline-flex items-center justify-between px-4 py-3 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold shadow-sm hover:shadow-md transition"
                    >
                        <span>Ajukan KGB Baru</span>
                        <i class="fas fa-file-circle-plus"></i>
                    </a>
                    <a
                        href="{{ route('pegawai.pengajuan.index') }}"
                        class="w-full inline-flex items-center justify-between px-4 py-3 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        <span>Lihat Riwayat Pengajuan</span>
                        <i class="fas fa-clock-rotate-left"></i>
                    </a>
                    <a
                        href="{{ route('pegawai.sk.index') }}"
                        class="w-full inline-flex items-center justify-between px-4 py-3 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        <span>SK KGB Saya</span>
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <span class="text-orange-500"><i class="fas fa-circle-info"></i></span>
                Informasi Pengajuan
            </h2>
            <p class="text-gray-600 text-sm">
                Untuk mengajukan Kenaikan Gaji Berkala, pastikan seluruh dokumen persyaratan sudah dipindai dalam format PDF
                dengan ukuran maksimal 1 MB per berkas. Klik tombol <span class="font-semibold">"Ajukan KGB Baru"</span> pada menu cepat
                untuk mengisi formulir pengajuan dan mengunggah dokumen.
            </p>
        </div>
    </div>
</div>
@endsection

