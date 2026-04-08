@extends('layouts.public')

@section('title', 'Pengajuan KGB Pegawai')

@section('content')
<section class="py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="gradient-bg text-white p-8">
                <h1 class="text-3xl font-bold">Form Pengajuan Kenaikan Gaji Berkala</h1>
                <p class="mt-2 opacity-95">
                    Data identitas Anda sudah terisi otomatis dari profil pegawai. Silakan lengkapi informasi TMT dan unggah berkas persyaratan.
                </p>
            </div>

            <form
                action="{{ route('pegawai.pengajuan.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-8 space-y-8"
            >
                @csrf

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
                            <div class="text-gray-500 mb-1">Pangkat Terakhir</div>
                            <div class="font-medium">{{ $user->pangkat_terakhir ?? '-' }}</div>
                        </div>

                        <div class="md:col-span-2 mt-4">
                            <label class="block font-medium mb-2">TMT Berkala Berikutnya</label>
                            <input
                                id="tmt_berkala_berikutnya"
                                type="date"
                                name="tmt_berkala_berikutnya"
                                value="{{ old('tmt_berkala_berikutnya') }}"
                                class="w-full border rounded-lg px-4 py-3"
                                required
                            >
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold mb-4">Upload Berkas Persyaratan</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium mb-2">PDF Surat Pengantar dari SKPD</label>
                            <input
                                id="surat_pengantar_skpd"
                                type="file"
                                name="surat_pengantar_skpd"
                                accept="application/pdf"
                                class="w-full border rounded-lg px-4 py-3"
                                required
                            >
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF Legalisir SK CPNS</label>
                            <input
                                id="sk_cpns_legalisir"
                                type="file"
                                name="sk_cpns_legalisir"
                                accept="application/pdf"
                                class="w-full border rounded-lg px-4 py-3"
                                required
                            >
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF SK Pangkat Terakhir Legalisir</label>
                            <input
                                id="sk_pangkat_terakhir_legalisir"
                                type="file"
                                name="sk_pangkat_terakhir_legalisir"
                                accept="application/pdf"
                                class="w-full border rounded-lg px-4 py-3"
                                required
                            >
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF SK Kenaikan Gaji Berkala Terakhir</label>
                            <input
                                id="kgb_terakhir"
                                type="file"
                                name="kgb_terakhir"
                                accept="application/pdf"
                                class="w-full border rounded-lg px-4 py-3"
                                required
                            >
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF SK Peninjauan Masa Kerja (Opsional)</label>
                            <input type="file" name="sk_peninjauan_masa_kerja" accept="application/pdf" class="w-full border rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">PDF SKP 1 Tahun Terakhir</label>
                            <input
                                id="skp_1_tahun_terakhir"
                                type="file"
                                name="skp_1_tahun_terakhir"
                                accept="application/pdf"
                                class="w-full border rounded-lg px-4 py-3"
                                required
                            >
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
                            Dengan mencentang kotak ini dan mengirim pengajuan, saya menyatakan bahwa seluruh data dan dokumen yang diunggah adalah benar,
                            lengkap, dan saya bertanggung jawab penuh atas kebenaran dokumen tersebut.
                        </label>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <button
                            id="btn_submit_pengajuan"
                            class="btn-primary text-white px-8 py-3 rounded-lg font-semibold opacity-60 cursor-not-allowed"
                            disabled
                        >
                            Kirim Pengajuan
                        </button>

                        <a href="{{ route('pegawai.pengajuan.index') }}" class="border border-gray-300 px-8 py-3 rounded-lg font-semibold text-gray-700">
                            Lihat Riwayat Pengajuan
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
    (function () {
        const requiredFields = [
            document.getElementById('tmt_berkala_berikutnya'),
            document.getElementById('surat_pengantar_skpd'),
            document.getElementById('sk_cpns_legalisir'),
            document.getElementById('sk_pangkat_terakhir_legalisir'),
            document.getElementById('kgb_terakhir'),
            document.getElementById('skp_1_tahun_terakhir'),
        ].filter(Boolean);

        const agreeCheckbox = document.getElementById('agree_responsibility');
        const submitBtn = document.getElementById('btn_submit_pengajuan');

        function allRequiredFilled() {
            if (!agreeCheckbox || !submitBtn) return false;

            const filesOk = requiredFields.every((field) => {
                if (!field) return false;
                if (field.type === 'file') {
                    return field.files && field.files.length > 0;
                }
                return field.value && field.value.trim() !== '';
            });

            return filesOk && agreeCheckbox.checked;
        }

        function updateButtonState() {
            const ready = allRequiredFilled();
            submitBtn.disabled = !ready;
            if (ready) {
                submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
            } else {
                submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
            }
        }

        requiredFields.forEach((field) => {
            const events = field.type === 'file' ? ['change'] : ['input', 'change'];
            events.forEach((evt) => field.addEventListener(evt, updateButtonState));
        });

        if (agreeCheckbox) {
            agreeCheckbox.addEventListener('change', updateButtonState);
        }

        updateButtonState();
    })();
</script>
@endpush

@endsection
