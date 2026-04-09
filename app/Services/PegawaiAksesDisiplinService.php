<?php

namespace App\Services;

use App\Models\DisiplinBlockLog;
use App\Models\DisiplinPegawai;
use App\Models\PegawaiSkpDuaTahun;
use App\Models\User;
use Illuminate\Support\Carbon;

class PegawaiAksesDisiplinService
{
    public static function normalizeNip(string $raw): string
    {
        $nip = preg_replace('/\s+/', '', $raw);
        $nip = ltrim((string) $nip, "'’`");
        $nip = preg_replace('/[^0-9]/', '', (string) $nip) ?? '';

        return $nip;
    }

    public static function predikatBuruk(string $predikat): bool
    {
        $n = mb_strtolower(trim($predikat));
        $n = preg_replace('/\s+/u', ' ', $n) ?? $n;

        return in_array($n, ['buruk', 'sangat buruk'], true);
    }

    /**
     * Pasangan tahun penilaian SKP yang dipakai untuk validasi (satu pasangan, sama dengan otomatis).
     *
     * @return list<array{0:int,1:int}>
     */
    public static function pasanganTahunSkpDiizinkan(?Carbon $now = null): array
    {
        $pair = self::pasanganTahunSkpOtomatis($now);

        return [$pair];
    }

    /**
     * Periode SKP otomatis (tanpa input admin): tahun (Y−1) dan tahun (Y−2), Y = tahun berjalan.
     * Indeks 0 = tahun lebih baru dalam periode, indeks 1 = tahun lebih lama.
     *
     * @return array{0:int,1:int}
     */
    public static function pasanganTahunSkpOtomatis(?Carbon $now = null): array
    {
        $now = $now ?? Carbon::now();
        $y = (int) $now->year;

        return [$y - 1, $y - 2];
    }

    public static function skpMemblokirAkses(?PegawaiSkpDuaTahun $skp): bool
    {
        if (! $skp) {
            return false;
        }

        return self::predikatBuruk($skp->predikat_terbaru)
            || self::predikatBuruk($skp->predikat_sebelumnya);
    }

    public static function alasanBlokirSkp(PegawaiSkpDuaTahun $skp): string
    {
        $parts = [];
        if (self::predikatBuruk($skp->predikat_terbaru)) {
            $parts[] = "SKP {$skp->tahun_terbaru}: {$skp->predikat_terbaru}";
        }
        if (self::predikatBuruk($skp->predikat_sebelumnya)) {
            $parts[] = "SKP {$skp->tahun_sebelumnya}: {$skp->predikat_sebelumnya}";
        }

        return 'Predikat SKP 2 tahun terakhir buruk/sangat buruk: '.implode('; ', $parts);
    }

    /**
     * Teks untuk ditampilkan di layar login (pegawai / NIP).
     */
    public static function pesanNotifikasiLoginDitolakSkp(PegawaiSkpDuaTahun $skp): string
    {
        $t1 = (int) $skp->tahun_terbaru;
        $t2 = (int) $skp->tahun_sebelumnya;
        $p1 = trim((string) $skp->predikat_terbaru);
        $p2 = trim((string) $skp->predikat_sebelumnya);

        $rincianPredikat = $p1 === $p2
            ? "Predikat 2 tahun terakhir: «{$p1}»."
            : "Rincian periode: tahun {$t1} — «{$p1}»; tahun {$t2} — «{$p2}».";

        return 'Anda tidak dapat masuk ke sistem karena hasil penilaian kinerja (SKP) pada 2 (dua) tahun penilaian terakhir '
            .'mencakup predikat Buruk atau Sangat Buruk, sesuai data yang tercatat. '
            .$rincianPredikat.' '
            ."Periode yang tercatat: tahun {$t1} dan {$t2}. "
            .'Silakan menghubungi BKPSDM apabila perlu klarifikasi.';
    }

    /**
     * Teks untuk penolakan pengajuan (konsisten dengan login).
     */
    public static function pesanNotifikasiPengajuanDitolakSkp(PegawaiSkpDuaTahun $skp): string
    {
        $t1 = (int) $skp->tahun_terbaru;
        $t2 = (int) $skp->tahun_sebelumnya;
        $p1 = trim((string) $skp->predikat_terbaru);
        $p2 = trim((string) $skp->predikat_sebelumnya);

        $rincianPredikat = $p1 === $p2
            ? "Predikat 2 tahun terakhir: «{$p1}»."
            : "Rincian periode: tahun {$t1} — «{$p1}»; tahun {$t2} — «{$p2}».";

        return 'Anda tidak dapat mengajukan KGB karena hasil penilaian kinerja (SKP) pada 2 (dua) tahun penilaian terakhir '
            .'mencakup predikat Buruk atau Sangat Buruk, sesuai data yang tercatat. '
            .$rincianPredikat.' '
            ."Periode yang tercatat: tahun {$t1} dan {$t2}. "
            .'Silakan menghubungi BKPSDM apabila perlu klarifikasi.';
    }

    public static function blokirLoginKarenaSkp(User $user): bool
    {
        $skp = PegawaiSkpDuaTahun::query()->where('user_id', $user->id)->first();

        return self::skpMemblokirAkses($skp);
    }

    public static function hukumanMemblokirLogin(User $user): ?DisiplinPegawai
    {
        return DisiplinPegawai::query()
            ->where('user_id', $user->id)
            ->where('selesai', false)
            ->whereIn('tingkat_hukuman', ['sedang', 'berat'])
            ->whereDate('tmt_berlaku', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('tmt_selesai')
                    ->orWhereDate('tmt_selesai', '>=', now()->toDateString());
            })
            ->latest('id')
            ->first();
    }

    public static function logBlokir(string $jenis, User $user, string $alasan, ?array $meta = null): void
    {
        DisiplinBlockLog::create([
            'nip' => (string) ($user->nip ?? ''),
            'nama_pegawai' => (string) $user->name,
            'jenis' => $jenis,
            'alasan' => mb_substr($alasan, 0, 500),
            'meta' => $meta,
        ]);
    }

    /**
     * Pesan blokir login (hukuman aktif atau SKP buruk). Null jika boleh login.
     */
    public static function pesanBlokirLogin(User $user): ?string
    {
        $skp = PegawaiSkpDuaTahun::query()->where('user_id', $user->id)->first();
        if (self::skpMemblokirAkses($skp)) {
            return self::pesanNotifikasiLoginDitolakSkp($skp);
        }

        $hukuman = self::hukumanMemblokirLogin($user);
        if ($hukuman) {
            $tmt = $hukuman->tmt_berlaku?->format('d/m/Y');
            $tingkat = $hukuman->tingkat_hukuman;

            return "Anda tidak dapat masuk ke sistem karena sedang menjalani hukuman disiplin tingkat {$tingkat} "
                ."(berlaku sejak {$tmt}). Silakan menghubungi BKPSDM apabila perlu klarifikasi.";
        }

        return null;
    }

    /**
     * Pesan blokir pengajuan (SKP saja sesuai permintaan; hukuman tetap memblokir).
     */
    public static function pesanBlokirPengajuan(User $user): ?string
    {
        $skp = PegawaiSkpDuaTahun::query()->where('user_id', $user->id)->first();
        if (self::skpMemblokirAkses($skp)) {
            return self::pesanNotifikasiPengajuanDitolakSkp($skp);
        }

        $hukuman = self::hukumanMemblokirLogin($user);
        if ($hukuman) {
            return 'Tidak dapat mengajukan KGB karena hukuman disiplin aktif (sedang/berat).';
        }

        return null;
    }
}
