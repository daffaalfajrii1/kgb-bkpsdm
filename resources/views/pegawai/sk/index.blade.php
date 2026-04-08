@extends('layouts.public')

@section('title', 'SK KGB Saya')

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
                <h1 class="text-2xl font-bold text-gray-800">SK Kenaikan Gaji Berkala Saya</h1>
                <p class="text-gray-600 text-sm mt-1">
                    Daftar SK KGB yang telah diterbitkan untuk NIP {{ $user->nip }} ({{ $user->name }}).
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-4">No</th>
                        <th class="text-left px-6 py-4">No Registrasi</th>
                        <th class="text-left px-6 py-4">Nama</th>
                        <th class="text-left px-6 py-4">Tanggal Upload</th>
                        <th class="text-left px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr class="border-t">
                            <td class="px-6 py-4">{{ $items->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">{{ $item->nomor_registrasi ?: '-' }}</td>
                            <td class="px-6 py-4">{{ $item->nama }}</td>
                            <td class="px-6 py-4">
                                {{ $item->tanggal_upload ? \Illuminate\Support\Carbon::parse($item->tanggal_upload)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('public.sk.download', $item->id) }}" class="text-orange-600 font-semibold">
                                    Unduh PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                                Belum ada SK KGB yang diterbitkan untuk NIP ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</section>
@endsection

