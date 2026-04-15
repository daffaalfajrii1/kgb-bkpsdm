@extends('layouts.public')

@section('title', 'Profil Pegawai')

@section('content')
<div class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('pegawai.dashboard') }}"
                    class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 text-xs font-medium text-gray-700 bg-white hover:bg-gray-50"
                >
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-800">Profil Pegawai</h1>
                <p class="text-gray-600 mt-2">
                    Kelola profil akun dan keamanan password Anda.
                </p>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <div class="text-xs text-gray-500">Total Pengajuan</div>
                <div class="text-2xl font-bold text-gray-800 mt-1">{{ $statistik['semua'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <div class="text-xs text-gray-500">Diajukan</div>
                <div class="text-2xl font-bold text-yellow-700 mt-1">{{ $statistik['diajukan'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <div class="text-xs text-gray-500">Diproses</div>
                <div class="text-2xl font-bold text-blue-700 mt-1">{{ $statistik['diproses'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <div class="text-xs text-gray-500">Ditolak</div>
                <div class="text-2xl font-bold text-red-700 mt-1">{{ $statistik['ditolak'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4">
                <div class="text-xs text-gray-500">Selesai</div>
                <div class="text-2xl font-bold text-green-700 mt-1">{{ $statistik['selesai'] }}</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-orange-500"><i class="fas fa-id-card"></i></span>
                    Data Profil
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
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-orange-500"><i class="fas fa-key"></i></span>
                    Ganti Password
                </h2>
                <form method="POST" action="{{ route('pegawai.profile.password.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Password Baru</label>
                        <input type="password" name="new_password" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required minlength="8">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required minlength="8">
                    </div>
                    <button type="submit" class="w-full btn-primary text-white px-4 py-2 rounded-lg text-sm font-semibold">
                        Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <span class="text-orange-500"><i class="fas fa-clock-rotate-left"></i></span>
                    Aktivitas Pengajuan Terbaru
                </h2>
                <a href="{{ route('pegawai.pengajuan.index') }}" class="text-sm text-orange-600 font-semibold hover:underline">
                    Lihat semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Nomor Registrasi</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pengajuanTerbaru as $item)
                            <tr>
                                <td class="px-4 py-3 text-gray-700">{{ $item->created_at?->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $item->nomor_registrasi }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $badgeColor = [
                                            'diajukan' => 'bg-yellow-100 text-yellow-800',
                                            'diproses' => 'bg-blue-100 text-blue-800',
                                            'selesai' => 'bg-green-100 text-green-800',
                                            'ditolak' => 'bg-red-100 text-red-800',
                                        ][$item->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                    Belum ada pengajuan yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

