<!DOCTYPE html>
<html lang="id">
<body style="font-family: Arial, sans-serif; color:#111827;">
    <p>Halo <strong>{{ $pegawai->name }}</strong>,</p>

    <p>
        Pengajuan Kenaikan Gaji Berkala Anda dengan nomor registrasi:
        <strong>{{ $pengajuan->nomor_registrasi }}</strong>
        telah <strong>dikembalikan untuk perbaikan</strong>.
    </p>

    <p><strong>Catatan/Alasan dari admin:</strong></p>
    <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:10px;padding:12px;">
        {{ $pengajuan->catatan_admin }}
    </div>

    @if (! empty($perbaikanLabels))
        <p style="margin-top:16px;"><strong>Item yang harus diperbaiki:</strong></p>
        <ul>
            @foreach ($perbaikanLabels as $label)
                <li>{{ $label }}</li>
            @endforeach
        </ul>
    @endif

    <p>
        Silakan login sebagai pegawai untuk mengunggah ulang berkas yang diminta, lalu kirim ulang pengajuan Anda.
    </p>

    <p>Terima kasih.</p>
    <p><strong>{{ config('app.name', 'KGB Online') }}</strong></p>
</body>
</html>

