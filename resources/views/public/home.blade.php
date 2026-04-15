@extends('layouts.public')

@section('title', 'Beranda Kenaikan Gaji Berkala Online')

@section('content')
<section class="gradient-bg text-white py-20">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Pelayanan Kenaikan Gaji Berkala Online</h1>
            <p class="text-lg mb-8 opacity-95">
                Ajukan berkas Kenaikan Gaji Berkala dan unduh SK Kenaikan Gaji Berkala yang terbaru.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('pegawai.login') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">Ajukan KGB</a>
            </div>
        </div>
        <div class="bg-white/10 rounded-2xl p-8 backdrop-blur">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div class="bg-white/10 rounded-xl p-5">
                    <div class="text-3xl font-bold">{{ $totalPengajuan }}</div>
                    <div class="text-sm mt-1">Total Pengajuan</div>
                </div>
                <div class="bg-white/10 rounded-xl p-5">
                    <div class="text-3xl font-bold">{{ $totalDiproses }}</div>
                    <div class="text-sm mt-1">Sedang Diproses</div>
                </div>
                <div class="bg-white/10 rounded-xl p-5">
                    <div class="text-3xl font-bold">{{ $totalSelesai }}</div>
                    <div class="text-sm mt-1">Selesai</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-50 border-y border-gray-200">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Informasi Syarat Kenaikan Gaji Berkala</h2>
            <div class="w-20 h-1 bg-orange-500 mx-auto mb-4"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-8 md:p-10 text-gray-700 leading-relaxed space-y-6">
            <p class="text-base md:text-lg text-gray-800">
                Kenaikan gaji berkala (KGB) bagi Pegawai Negeri Sipil (PNS) / PPPK diatur oleh peraturan pemerintah dan memerlukan beberapa syarat utama untuk diajukan.
            </p>

            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="text-orange-500"><i class="fas fa-clipboard-list"></i></span>
                    Syarat Umum Kenaikan Gaji Berkala
                </h3>
                <ol class="list-decimal list-outside pl-5 md:pl-6 space-y-4 marker:text-orange-500 marker:font-semibold">
                    <li>
                        <span class="font-medium text-gray-900">Masa kerja</span> — PNS/PPPK harus telah mencapai masa kerja minimum dua tahun sejak kenaikan gaji terakhir.
                    </li>
                    <li>
                        <span class="font-medium text-gray-900">Penilaian kinerja</span> — PNS/PPPK harus memiliki penilaian kinerja yang baik, yang dibuktikan dengan dokumen Sasaran Kerja Pegawai (SKP).
                    </li>
                    <li>
                        <span class="font-medium text-gray-900">Status aktif</span> — PNS/PPPK harus masih berstatus aktif dan tidak sedang menjalani hukuman disiplin.
                    </li>
                    <li>
                        PNS yang sedang cuti di luar tanggungan negara atau diberhentikan sementara tidak berhak mendapatkan KGB.
                    </li>
                    <li>
                        <span class="font-medium text-gray-900">Dokumen pendukung</span> — Dokumen yang diperlukan untuk pengajuan KGB meliputi:
                        <ul class="mt-3 list-disc list-outside pl-5 space-y-2 text-gray-700">
                            <li>Surat pengantar yang ditandatangani oleh Kepala OPD/Instansi</li>
                            <li>Surat Keputusan (SK) CPNS</li>
                            <li>SK pangkat terakhir</li>
                            <li>SK berkala terakhir</li>
                            <li>SKP 1 tahun terakhir</li>
                        </ul>
                        <p class="mt-4 rounded-lg bg-orange-50 border border-orange-100 px-4 py-3 text-sm text-gray-800">
                            <i class="fas fa-file-pdf text-orange-600 mr-2"></i>
                            Dokumen pendukung dapat diunggah dalam format <strong>PDF</strong> (maksimal <strong>1 MB</strong> per berkas).
                        </p>
                    </li>
                    <li>
                        <span class="font-medium text-gray-900">Waktu pengajuan</span> — Daftar usulan diajukan <strong>2 bulan sebelum</strong> TMT berkala berikutnya.
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Layanan Publik KGB</h2>
            <div class="w-20 h-1 bg-orange-500 mx-auto mb-4"></div>
            <p class="text-gray-600">Tiga layanan utama untuk memudahkan pengajuan dan pelacakan KGB.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="service-card bg-white p-6 rounded-xl shadow-md">
                <div class="text-orange-500 text-4xl mb-4"><i class="fas fa-file-circle-plus"></i></div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Pengajuan KGB</h3>
                <p class="text-gray-600 mb-4">Isi form pengajuan dan unggah seluruh berkas persyaratan secara online.</p>
                <a href="{{ route('pegawai.login') }}" class="text-orange-500 font-medium">Buka Form</a>
            </div>

            <div class="service-card bg-white p-6 rounded-xl shadow-md">
                <div class="text-orange-500 text-4xl mb-4"><i class="fas fa-file-pdf"></i></div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">SK Kenaikan Gaji Berkala Selesai</h3>
                <p class="text-gray-600 mb-4">Lihat daftar SK Kenaikan Gaji Berkala yang sudah selesai dan unduh file PDF-nya.</p>
                <a href="{{ route('public.sk.index') }}" class="text-orange-500 font-medium">Lihat SK</a>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-1 gap-8">
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-2xl font-semibold mb-4">SK Kenaikan Gaji Berkala Terbaru</h3>
            <div class="space-y-4">
                @forelse ($latestResults as $item)
                    <div class="border rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <div class="font-semibold">{{ $item->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $item->nomor_registrasi ?: '-' }}</div>
                        </div>
                        <a href="{{ route('public.sk.download', $item->id) }}" class="text-orange-600 font-medium">Unduh</a>
                    </div>
                @empty
                    <div class="text-gray-500">Belum ada SK Kenaikan Gaji Berkala selesai.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection