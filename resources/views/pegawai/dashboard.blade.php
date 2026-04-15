@extends('layouts.public')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="py-8 md:py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-slate-900 via-slate-800 to-orange-700 text-white shadow-xl mb-8">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.18),_transparent_30%)]"></div>
            <div class="relative p-6 md:p-8">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-medium mb-4">
                            <i class="fas fa-sparkles text-amber-300"></i>
                            Portal layanan pegawai
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold leading-tight">Dashboard Pegawai</h1>
                        <p class="text-slate-200 mt-3 text-sm md:text-base leading-6">
                            Selamat datang,
                            <span class="font-semibold text-white">
                                {{ trim(($user->gelar_depan ? $user->gelar_depan.' ' : '').$user->name.' '.($user->gelar_belakang ?? '')) }}
                            </span>.
                            Pantau status pengajuan KGB, akses dokumen, dan kelola akun Anda dari satu tempat.
                        </p>

                        <div class="grid sm:grid-cols-3 gap-3 mt-6">
                            <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3">
                                <div class="text-xs uppercase tracking-wide text-slate-300">NIP</div>
                                <div class="font-semibold mt-1">{{ $user->nip ?? '-' }}</div>
                            </div>
                            <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3">
                                <div class="text-xs uppercase tracking-wide text-slate-300">Unit Kerja</div>
                                <div class="font-semibold mt-1">{{ $user->dinas_instansi ?? '-' }}</div>
                            </div>
                            <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3">
                                <div class="text-xs uppercase tracking-wide text-slate-300">Golongan</div>
                                <div class="font-semibold mt-1">{{ $user->gol_pangkat ?? $user->pangkat_terakhir ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-auto flex flex-col sm:flex-row lg:flex-col gap-3">
                        <a
                            href="{{ route('pegawai.profile.show') }}"
                            class="inline-flex items-center justify-center px-4 py-3 rounded-xl bg-white text-slate-800 text-sm font-semibold hover:bg-slate-100 transition"
                        >
                            <i class="fas fa-user-shield mr-2"></i> Profil & Password
                        </a>
                        @if ($bolehAjukan)
                            <a
                                href="{{ route('pegawai.pengajuan.create') }}"
                                class="inline-flex items-center justify-center px-4 py-3 rounded-xl bg-orange-500 text-white text-sm font-semibold hover:bg-orange-400 transition"
                            >
                                <i class="fas fa-file-circle-plus mr-2"></i> Ajukan KGB Baru
                            </a>
                        @else
                            <span class="inline-flex items-center justify-center px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-sm font-semibold text-slate-200">
                                <i class="fas fa-lock mr-2"></i> Pengajuan Sementara Nonaktif
                            </span>
                        @endif
                        <form method="POST" action="{{ route('pegawai.logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl border border-white/20 text-sm font-semibold text-white hover:bg-white/10 transition"
                            >
                                <i class="fas fa-right-from-bracket mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
            <div class="col-span-2 lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-slate-500">Total Pengajuan</div>
                        <div class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['total_pengajuan'] }}</div>
                    </div>
                    <span class="h-11 w-11 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <i class="fas fa-folder-open"></i>
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-3">Ringkasan seluruh pengajuan KGB Anda.</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-slate-500">Menunggu Review</div>
                        <div class="text-3xl font-bold text-amber-600 mt-2">{{ $stats['diajukan'] }}</div>
                    </div>
                    <span class="h-11 w-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="fas fa-hourglass-half"></i>
                    </span>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-slate-500">Sedang Diproses</div>
                        <div class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['diproses'] }}</div>
                    </div>
                    <span class="h-11 w-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-gear"></i>
                    </span>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-slate-500">Perlu Perbaikan</div>
                        <div class="text-3xl font-bold text-red-600 mt-2">{{ $stats['ditolak'] }}</div>
                    </div>
                    <span class="h-11 w-11 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                        <i class="fas fa-triangle-exclamation"></i>
                    </span>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-slate-500">Pengajuan Selesai</div>
                        <div class="text-3xl font-bold text-green-600 mt-2">{{ $stats['selesai'] }}</div>
                    </div>
                    <span class="h-11 w-11 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                        <i class="fas fa-circle-check"></i>
                    </span>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-slate-500">SK Terbit</div>
                        <div class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['sk_terbit'] }}</div>
                    </div>
                    <span class="h-11 w-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                        <i class="fas fa-file-pdf"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="grid xl:grid-cols-3 gap-6 mb-8">
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Aktivitas Pengajuan Terbaru</h2>
                            <p class="text-sm text-slate-500 mt-1">Pantau progres pengajuan terakhir Anda secara cepat.</p>
                        </div>
                        <a href="{{ route('pegawai.pengajuan.index') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700">
                            Lihat semua
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($pengajuanTerbaru as $item)
                            @php
                                $statusConfig = [
                                    'diajukan' => ['label' => 'Diajukan', 'dot' => 'bg-amber-500', 'badge' => 'bg-amber-100 text-amber-700'],
                                    'diproses' => ['label' => 'Diproses', 'dot' => 'bg-blue-500', 'badge' => 'bg-blue-100 text-blue-700'],
                                    'ditolak' => ['label' => 'Ditolak', 'dot' => 'bg-red-500', 'badge' => 'bg-red-100 text-red-700'],
                                    'selesai' => ['label' => 'Selesai', 'dot' => 'bg-green-500', 'badge' => 'bg-green-100 text-green-700'],
                                ][$item->status] ?? ['label' => ucfirst($item->status), 'dot' => 'bg-slate-400', 'badge' => 'bg-slate-100 text-slate-700'];
                            @endphp
                            <div class="flex items-start gap-4 rounded-2xl border border-slate-100 bg-slate-50/80 p-4 hover:bg-white hover:shadow-sm transition">
                                <div class="mt-1 h-3 w-3 rounded-full {{ $statusConfig['dot'] }}"></div>
                                <div class="flex-1">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                        <div>
                                            <div class="font-semibold text-slate-800">{{ $item->nomor_registrasi }}</div>
                                            <div class="text-sm text-slate-500">
                                                Diajukan pada {{ $item->created_at?->format('d/m/Y') }} untuk TMT {{ \Illuminate\Support\Carbon::parse($item->tmt_berkala_berikutnya)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusConfig['badge'] }}">
                                            {{ $statusConfig['label'] }}
                                        </span>
                                    </div>
                                    @if ($item->catatan_admin)
                                        <div class="mt-3 text-sm text-slate-600 rounded-xl bg-white border border-slate-200 px-3 py-2">
                                            <span class="font-semibold text-slate-700">Catatan admin:</span> {{ $item->catatan_admin }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                                <div class="text-slate-400 text-3xl mb-3"><i class="fas fa-inbox"></i></div>
                                <div class="font-semibold text-slate-700">Belum ada pengajuan tercatat</div>
                                <p class="text-sm text-slate-500 mt-1">Mulai pengajuan pertama Anda dari menu cepat di dashboard.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">Menu Cepat</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <a href="{{ route('pegawai.profile.show') }}" class="group rounded-2xl border border-slate-200 p-5 hover:border-orange-300 hover:shadow-sm transition">
                            <div class="flex items-center justify-between">
                                <span class="h-11 w-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                                <i class="fas fa-arrow-right text-slate-300 group-hover:text-orange-500 transition"></i>
                            </div>
                            <div class="mt-4 font-semibold text-slate-800">Profil & Keamanan</div>
                            <p class="text-sm text-slate-500 mt-1">Lihat profil dan ubah password akun pegawai.</p>
                        </a>
                        <a href="{{ route('pegawai.pengajuan.index') }}" class="group rounded-2xl border border-slate-200 p-5 hover:border-orange-300 hover:shadow-sm transition">
                            <div class="flex items-center justify-between">
                                <span class="h-11 w-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                    <i class="fas fa-clock-rotate-left"></i>
                                </span>
                                <i class="fas fa-arrow-right text-slate-300 group-hover:text-orange-500 transition"></i>
                            </div>
                            <div class="mt-4 font-semibold text-slate-800">Riwayat Pengajuan</div>
                            <p class="text-sm text-slate-500 mt-1">Pantau seluruh status pengajuan KGB Anda.</p>
                        </a>
                        <a href="{{ route('pegawai.sk.index') }}" class="group rounded-2xl border border-slate-200 p-5 hover:border-orange-300 hover:shadow-sm transition">
                            <div class="flex items-center justify-between">
                                <span class="h-11 w-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                                <i class="fas fa-arrow-right text-slate-300 group-hover:text-orange-500 transition"></i>
                            </div>
                            <div class="mt-4 font-semibold text-slate-800">SK KGB Saya</div>
                            <p class="text-sm text-slate-500 mt-1">Akses dan unduh SK KGB yang sudah terbit.</p>
                        </a>
                        @if ($bolehAjukan)
                            <a href="{{ route('pegawai.pengajuan.create') }}" class="group rounded-2xl border border-orange-200 bg-orange-50 p-5 hover:bg-orange-100 transition">
                                <div class="flex items-center justify-between">
                                    <span class="h-11 w-11 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                                        <i class="fas fa-file-circle-plus"></i>
                                    </span>
                                    <i class="fas fa-arrow-right text-orange-400"></i>
                                </div>
                                <div class="mt-4 font-semibold text-slate-800">Ajukan KGB Baru</div>
                                <p class="text-sm text-slate-600 mt-1">Mulai proses pengajuan KGB baru sekarang.</p>
                            </a>
                        @else
                            <div class="rounded-2xl border border-slate-200 bg-slate-100 p-5">
                                <div class="flex items-center justify-between">
                                    <span class="h-11 w-11 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <div class="mt-4 font-semibold text-slate-700">Pengajuan Baru Nonaktif</div>
                                <p class="text-sm text-slate-500 mt-1">Akses pengajuan baru sedang dibatasi berdasarkan status pegawai.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">Status Akun</h2>

                    @if ($bolehAjukan)
                        <div class="rounded-2xl bg-green-50 border border-green-200 p-4">
                            <div class="flex items-start gap-3">
                                <span class="h-10 w-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                                    <i class="fas fa-circle-check"></i>
                                </span>
                                <div>
                                    <div class="font-semibold text-green-800">Akun aktif untuk pengajuan</div>
                                    <p class="text-sm text-green-700 mt-1">Anda dapat mengajukan KGB baru dan memantau statusnya dari dashboard ini.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl bg-red-50 border border-red-200 p-4">
                            <div class="flex items-start gap-3">
                                <span class="h-10 w-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                                    <i class="fas fa-ban"></i>
                                </span>
                                <div>
                                    <div class="font-semibold text-red-800">Pengajuan sedang dibatasi</div>
                                    <p class="text-sm text-red-700 mt-1">{{ $pesanBlokir }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-5 space-y-4 text-sm">
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <div class="text-slate-500">Email aktif</div>
                            <div class="font-semibold text-slate-800 mt-1">{{ $user->email ?? '-' }}</div>
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <div class="text-slate-500">Alamat</div>
                            <div class="font-semibold text-slate-800 mt-1">{{ $user->alamat ?? '-' }}</div>
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <div class="text-slate-500">Masa kerja</div>
                            <div class="font-semibold text-slate-800 mt-1">
                                @if ($user->mk_tahun !== null || $user->mk_bulan !== null)
                                    {{ (int) ($user->mk_tahun ?? 0) }} tahun {{ (int) ($user->mk_bulan ?? 0) }} bulan
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">Pengajuan Terakhir</h2>
                    @if ($pengajuanTerakhir)
                        @php
                            $progress = match($pengajuanTerakhir->status) {
                                'diajukan' => 25,
                                'diproses' => 65,
                                'ditolak' => 100,
                                'selesai' => 100,
                                default => 10,
                            };
                            $progressColor = match($pengajuanTerakhir->status) {
                                'diajukan' => 'bg-amber-500',
                                'diproses' => 'bg-blue-500',
                                'ditolak' => 'bg-red-500',
                                'selesai' => 'bg-green-500',
                                default => 'bg-slate-500',
                            };
                        @endphp
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <div class="font-semibold text-slate-800">{{ $pengajuanTerakhir->nomor_registrasi }}</div>
                            <div class="text-sm text-slate-500 mt-1">
                                Status saat ini: <span class="font-semibold text-slate-700">{{ ucfirst($pengajuanTerakhir->status) }}</span>
                            </div>
                            <div class="mt-4 h-2.5 w-full rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-full rounded-full {{ $progressColor }}" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="mt-2 text-xs text-slate-500">Estimasi progres pemrosesan: {{ $progress }}%</div>

                            @if ($pengajuanTerakhir->catatan_admin)
                                <div class="mt-4 rounded-xl bg-white border border-slate-200 p-3 text-sm text-slate-600">
                                    <span class="font-semibold text-slate-700">Catatan admin:</span> {{ $pengajuanTerakhir->catatan_admin }}
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">
                            Belum ada pengajuan terbaru untuk ditampilkan.
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-3">Informasi Pengajuan</h2>
                    <p class="text-sm text-slate-600 leading-6">
                        Pastikan seluruh dokumen persyaratan dipindai dalam format PDF dan terbaca dengan jelas sebelum dikirim.
                        Jika pengajuan ditolak, Anda bisa memperbaiki berkas lalu mengirim ulang melalui menu riwayat pengajuan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

