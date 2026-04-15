@extends('layouts.public')

@section('title', 'Perbaikan Pengajuan KGB')

@section('content')
<section class="py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="gradient-bg text-white p-8">
                <h1 class="text-3xl font-bold">Perbaikan Pengajuan KGB</h1>
                <p class="mt-2 opacity-95">
                    Pengajuan ini dikembalikan oleh admin. Unggah ulang berkas yang perlu diperbaiki lalu kirim ulang.
                </p>
            </div>

            <form
                action="{{ route('pegawai.pengajuan.update', $pengajuan->id) }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-8 space-y-8"
            >
                @csrf
                @method('PATCH')

                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    <div class="font-semibold mb-1">Catatan perbaikan dari admin:</div>
                    <div>{{ $pengajuan->catatan_admin ?: 'Tidak ada catatan.' }}</div>
                </div>

                @if (!empty($requiredFixes))
                    <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                        <div class="font-semibold mb-1">Item wajib diperbaiki:</div>
                        <ul class="list-disc pl-5">
                            @foreach ($requiredFixes as $fix)
                                <li>
                                    @switch($fix)
                                        @case('tmt_berkala_berikutnya') TMT berkala berikutnya @break
                                        @case('surat_pengantar_skpd') Surat Pengantar SKPD @break
                                        @case('sk_cpns_legalisir') SK CPNS legalisir @break
                                        @case('sk_pangkat_terakhir_legalisir') SK Pangkat terakhir legalisir @break
                                        @case('kgb_terakhir') KGB terakhir @break
                                        @case('sk_peninjauan_masa_kerja') SK Peninjauan Masa Kerja @break
                                        @case('skp_1_tahun_terakhir') SKP 1 tahun terakhir @break
                                        @default {{ $fix }}
                                    @endswitch
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <h2 class="text-xl font-semibold mb-4">Data Pegawai</h2>
                    <div class="grid md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <div class="text-gray-500 mb-1">Nama Lengkap</div>
                            <div class="font-medium">{{ $user->name }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 mb-1">NIP</div>
                            <div class="font-medium">{{ $user->nip }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 mb-1">Dinas / Instansi</div>
                            <div class="font-medium">{{ $user->dinas_instansi ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 mb-1">Gol. / Pangkat</div>
                            <div class="font-medium">{{ $user->gol_pangkat ?? $user->pangkat_terakhir ?? '-' }}</div>
                        </div>
                        <div class="md:col-span-2 mt-4">
                            <label class="block font-medium mb-2">TMT Berkala Berikutnya</label>
                            <input
                                id="tmt_berkala_berikutnya"
                                type="date"
                                name="tmt_berkala_berikutnya"
                                value="{{ old('tmt_berkala_berikutnya', \Illuminate\Support\Carbon::parse($pengajuan->tmt_berkala_berikutnya)->format('Y-m-d')) }}"
                                class="w-full border rounded-lg px-4 py-3"
                                {{ in_array('tmt_berkala_berikutnya', $requiredFixes ?? [], true) ? 'required' : '' }}
                            >
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold mb-2">Upload Ulang Berkas (yang diperbaiki)</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        Unggah minimal satu berkas yang diperbaiki. Berkas yang tidak diunggah ulang akan tetap memakai file lama.
                    </p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium mb-2">PDF Surat Pengantar dari SKPD</label>
                            <input type="file" name="surat_pengantar_skpd" accept="application/pdf" class="w-full border rounded-lg px-4 py-3" {{ in_array('surat_pengantar_skpd', $requiredFixes ?? [], true) ? 'required' : '' }}>
                        </div>
                        <div>
                            <label class="block font-medium mb-2">PDF Legalisir SK CPNS</label>
                            <input type="file" name="sk_cpns_legalisir" accept="application/pdf" class="w-full border rounded-lg px-4 py-3" {{ in_array('sk_cpns_legalisir', $requiredFixes ?? [], true) ? 'required' : '' }}>
                        </div>
                        <div>
                            <label class="block font-medium mb-2">PDF SK Pangkat Terakhir Legalisir</label>
                            <input type="file" name="sk_pangkat_terakhir_legalisir" accept="application/pdf" class="w-full border rounded-lg px-4 py-3" {{ in_array('sk_pangkat_terakhir_legalisir', $requiredFixes ?? [], true) ? 'required' : '' }}>
                        </div>
                        <div>
                            <label class="block font-medium mb-2">PDF SK Kenaikan Gaji Berkala Terakhir</label>
                            <input type="file" name="kgb_terakhir" accept="application/pdf" class="w-full border rounded-lg px-4 py-3" {{ in_array('kgb_terakhir', $requiredFixes ?? [], true) ? 'required' : '' }}>
                        </div>
                        <div>
                            <label class="block font-medium mb-2">PDF SK Peninjauan Masa Kerja (Opsional)</label>
                            <input type="file" name="sk_peninjauan_masa_kerja" accept="application/pdf" class="w-full border rounded-lg px-4 py-3" {{ in_array('sk_peninjauan_masa_kerja', $requiredFixes ?? [], true) ? 'required' : '' }}>
                        </div>
                        <div>
                            <label class="block font-medium mb-2">PDF SKP 1 Tahun Terakhir</label>
                            <input type="file" name="skp_1_tahun_terakhir" accept="application/pdf" class="w-full border rounded-lg px-4 py-3" {{ in_array('skp_1_tahun_terakhir', $requiredFixes ?? [], true) ? 'required' : '' }}>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-2 text-xs text-gray-600 bg-orange-50 border border-orange-100 rounded-lg px-4 py-3">
                        <input
                            type="checkbox"
                            name="agree_responsibility"
                            id="agree_responsibility"
                            class="mt-1 rounded border-orange-400 text-orange-500 focus:ring-orange-500"
                            required
                        >
                        <label for="agree_responsibility" class="cursor-pointer">
                            Saya menyatakan berkas perbaikan yang diunggah adalah benar dan menjadi tanggung jawab saya.
                        </label>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <button class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                            Kirim Ulang Pengajuan
                        </button>
                        <a href="{{ route('pegawai.pengajuan.index') }}" class="border border-gray-300 px-8 py-3 rounded-lg font-semibold text-gray-700">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
