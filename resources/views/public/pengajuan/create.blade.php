@extends('layouts.public')

@section('title', 'Pengajuan KGB')

@section('content')
<section class="py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="gradient-bg text-white p-8">
                <h1 class="text-3xl font-bold">Form Pengajuan Kenaikan Gaji Berkala</h1>
                <p class="mt-2 opacity-95">Lengkapi data pegawai dan unggah seluruh berkas persyaratan dalam format PDF.</p>
            </div>

            <form action="{{ route('public.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf

                <div>
                    <h2 class="text-xl font-semibold mb-4">Data Pegawai</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip') }}" class="w-full border rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">Dinas / Instansi</label>
                            <input type="text" name="dinas_instansi" value="{{ old('dinas_instansi') }}" class="w-full border rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">Pangkat Terakhir</label>
                            <select name="pangkat_terakhir" class="w-full border rounded-lg px-4 py-3">
                                <option value="">-- Pilih Pangkat --</option>
                                @foreach ($pangkatOptions as $item)
                                    <option value="{{ $item }}" {{ old('pangkat_terakhir') == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-medium mb-2">TMT Berkala Berikutnya</label>
                            <input type="date" name="tmt_berkala_berikutnya" value="{{ old('tmt_berkala_berikutnya') }}" class="w-full border rounded-lg px-4 py-3">
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold mb-4">Upload Berkas Persyaratan</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium mb-2">PDF Surat Pengantar dari SKPD</label>
                            <input type="file" name="surat_pengantar_skpd" accept="application/pdf" class="w-full border rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF Legalisir SK CPNS</label>
                            <input type="file" name="sk_cpns_legalisir" accept="application/pdf" class="w-full border rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF SK Pangkat Terakhir Legalisir</label>
                            <input type="file" name="sk_pangkat_terakhir_legalisir" accept="application/pdf" class="w-full border rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF SK Kenaikan Gaji Berkala Terakhir</label>
                            <input type="file" name="kgb_terakhir" accept="application/pdf" class="w-full border rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF SK Peninjauan Masa Kerja (Opsional)</label>
                            <input type="file" name="sk_peninjauan_masa_kerja" accept="application/pdf" class="w-full border rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF SKP 1 Tahun Terakhir</label>
                            <input type="file" name="skp_1_tahun_terakhir" accept="application/pdf" class="w-full border rounded-lg px-4 py-3">
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <button class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                        Kirim Pengajuan
                    </button>

                    <a href="{{ route('public.sk.index') }}" class="border border-gray-300 px-8 py-3 rounded-lg font-semibold text-gray-700">
                        Lihat SK Terbaru
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection