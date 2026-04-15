<!DOCTYPE html>
<html lang="id">
<body style="font-family: Arial, sans-serif; color:#111827;">
    <p>Halo <strong>{{ $pegawai->name }}</strong>,</p>

    <p>
        Hasil Kenaikan Gaji Berkala untuk pengajuan Anda dengan nomor registrasi:
        <strong>{{ $pengajuan->nomor_registrasi }}</strong>
        telah <strong>tersedia</strong>.
    </p>

    @if ($hasilKgb)
        <p>
            File hasil sudah siap untuk diunduh:
            <br>
            <a href="{{ $downloadUrl }}" style="color:#f97316; font-weight:600;">
                Unduh SK KGB
            </a>
        </p>
    @endif

    <p>
        Terima kasih telah menggunakan layanan ini.
    </p>

    <p><strong>{{ config('app.name', 'KGB Online') }}</strong></p>
</body>
</html>

