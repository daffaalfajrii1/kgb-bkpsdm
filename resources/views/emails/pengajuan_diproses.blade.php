<!DOCTYPE html>
<html lang="id">
<body style="font-family: Arial, sans-serif; color:#111827;">
    <p>Halo <strong>{{ $pegawai->name }}</strong>,</p>

    <p>
        Pengajuan Kenaikan Gaji Berkala Anda dengan nomor registrasi:
        <strong>{{ $pengajuan->nomor_registrasi }}</strong>
        saat ini <strong>sedang diproses</strong>.
    </p>

    <ul>
        <li>Nama: {{ $pengajuan->nama }}</li>
        <li>NIP: {{ $pengajuan->nip }}</li>
        <li>Instansi: {{ $pengajuan->dinas_instansi }}</li>
    </ul>

    <p>
        Silakan pantau status pengajuan melalui akun pegawai Anda.
        Jika ada permintaan perbaikan, Anda akan diarahkan pada proses berikutnya.
    </p>

    <p>Terima kasih.</p>
    <p><strong>{{ config('app.name', 'KGB Online') }}</strong></p>
</body>
</html>

