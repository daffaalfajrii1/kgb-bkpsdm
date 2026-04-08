@extends('layouts.public')

@section('title', 'Riwayat Pengajuan KGB')

@section('content')
<section class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    onclick="window.history.back()"
                    class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 text-xs font-medium text-gray-700 bg-white hover:bg-gray-50"
                >
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </button>
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800">Riwayat Pengajuan KGB</h1>
                <p class="text-gray-600 text-sm mt-1">
                    Menampilkan seluruh pengajuan KGB yang Anda ajukan menggunakan NIP {{ $user->nip }}.
                </p>
            </div>
            <div class="flex gap-3 justify-end">
                <a href="{{ route('pegawai.pengajuan.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    Ajukan KGB Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal Pengajuan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">TMT Berkala Berikutnya</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Pesan Admin</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">SK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pengajuans as $item)
                        <tr>
                            <td class="px-4 py-3 text-gray-800">
                                {{ $item->created_at?->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $item->tmt_berkala_berikutnya ? \Illuminate\Support\Carbon::parse($item->tmt_berkala_berikutnya)->format('d/m/Y') : '-' }}
                            </td>
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
                            <td class="px-4 py-3 text-gray-700 max-w-xs">
                                @if ($item->catatan_admin)
                                    <span class="block text-xs text-gray-500 mb-1">Pesan dari admin:</span>
                                    <span>{{ $item->catatan_admin }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">Belum ada pesan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($item->hasilKgb)
                                    <a href="{{ route('public.sk.download', $item->hasilKgb->id) }}" class="text-orange-600 text-sm font-semibold">
                                        Unduh SK
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Belum tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada pengajuan KGB yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pengajuans->links() }}
        </div>
    </div>
</section>
@endsection

