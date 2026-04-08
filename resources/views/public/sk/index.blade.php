@extends('layouts.public')

@section('title', 'SK KGB Selesai')

@section('content')
<section class="py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="p-8 border-b">
                <h1 class="text-3xl font-bold mb-4">Daftar SK KGB Selesai</h1>
                <form action="{{ route('public.sk.index') }}" method="GET" class="grid md:grid-cols-[1fr_auto] gap-4">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="border rounded-lg px-4 py-3" placeholder="Cari nomor registrasi / nama / NIP">
                    <button class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">Cari</button>
                </form>
            </div>

            <div class="overflow-x-auto">
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
                                <td class="px-6 py-4">{{ $item->tanggal_upload ? \Carbon\Carbon::parse($item->tanggal_upload)->format('d-m-Y') : '-' }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('public.sk.download', $item->id) }}" class="text-orange-600 font-semibold">Unduh PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">Belum ada SK KGB selesai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    </div>
</section>
@endsection