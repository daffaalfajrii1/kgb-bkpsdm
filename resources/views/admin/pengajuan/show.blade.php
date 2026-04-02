@extends('layouts.admin')

@section('title', 'Detail Pengajuan')
@section('page_title', 'Detail Pengajuan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Informasi Pengajuan</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="260">Nomor Registrasi</th>
                <td>{{ $pengajuan->nomor_registrasi }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $pengajuan->nama }}</td>
            </tr>
            <tr>
                <th>NIP</th>
                <td>{{ $pengajuan->nip }}</td>
            </tr>
            <tr>
                <th>Dinas / Instansi</th>
                <td>{{ $pengajuan->dinas_instansi }}</td>
            </tr>
            <tr>
                <th>Pangkat Terakhir</th>
                <td>{{ $pengajuan->pangkat_terakhir }}</td>
            </tr>
            <tr>
                <th>TMT Berkala Berikutnya</th>
                <td>
                    {{ $pengajuan->tmt_berkala_berikutnya ? \Carbon\Carbon::parse($pengajuan->tmt_berkala_berikutnya)->format('d-m-Y') : '-' }}
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($pengajuan->status === 'diajukan')
                        <span class="badge badge-secondary">Diajukan</span>
                    @elseif ($pengajuan->status === 'diproses')
                        <span class="badge badge-warning">Diproses</span>
                    @elseif ($pengajuan->status === 'selesai')
                        <span class="badge badge-success">Selesai</span>
                    @else
                        <span class="badge badge-dark">{{ $pengajuan->status }}</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Berkas Pengajuan</h3>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="260">Surat Pengantar SKPD</th>
                <td>
                    @if($pengajuan->surat_pengantar_skpd)
                        <a href="{{ asset('storage/'.$pengajuan->surat_pengantar_skpd) }}" target="_blank" class="btn btn-primary btn-sm">Lihat PDF</a>
                    @else
                        <span class="text-muted">Belum ada</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>SK CPNS Legalisir</th>
                <td>
                    @if($pengajuan->sk_cpns_legalisir)
                        <a href="{{ asset('storage/'.$pengajuan->sk_cpns_legalisir) }}" target="_blank" class="btn btn-primary btn-sm">Lihat PDF</a>
                    @else
                        <span class="text-muted">Belum ada</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>SK Pangkat Terakhir Legalisir</th>
                <td>
                    @if($pengajuan->sk_pangkat_terakhir_legalisir)
                        <a href="{{ asset('storage/'.$pengajuan->sk_pangkat_terakhir_legalisir) }}" target="_blank" class="btn btn-primary btn-sm">Lihat PDF</a>
                    @else
                        <span class="text-muted">Belum ada</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>SK Kenaikan Gaji Berkala Terakhir</th>
                <td>
                    @if($pengajuan->kgb_terakhir)
                        <a href="{{ asset('storage/'.$pengajuan->kgb_terakhir) }}" target="_blank" class="btn btn-primary btn-sm">Lihat PDF</a>
                    @else
                        <span class="text-muted">Belum ada</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>SK Peninjauan Masa Kerja</th>
                <td>
                    @if($pengajuan->sk_peninjauan_masa_kerja)
                        <a href="{{ asset('storage/'.$pengajuan->sk_peninjauan_masa_kerja) }}" target="_blank" class="btn btn-primary btn-sm">Lihat PDF</a>
                    @else
                        <span class="text-muted">Opsional / belum ada</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>SKP 1 Tahun Terakhir</th>
                <td>
                    @if($pengajuan->skp_1_tahun_terakhir)
                        <a href="{{ asset('storage/'.$pengajuan->skp_1_tahun_terakhir) }}" target="_blank" class="btn btn-primary btn-sm">Lihat PDF</a>
                    @else
                        <span class="text-muted">Belum ada</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Aksi Pengajuan</h3>
    </div>

    <div class="card-body">
        @if ($pengajuan->status === 'diajukan')
            <form action="{{ route('admin.pengajuan.proses', $pengajuan->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('PATCH')
                <button class="btn btn-warning" onclick="return confirm('Tandai pengajuan ini sebagai diproses?')">
                    Tandai Diproses
                </button>
            </form>
        @endif

        @if ($pengajuan->status === 'diproses')
            <a href="{{ route('admin.hasil-kgb.create') }}" class="btn btn-success">
                Upload Hasil KGB
            </a>
        @endif

        @if ($pengajuan->hasilKgb)
            <a href="{{ asset('storage/'.$pengajuan->hasilKgb->file_hasil) }}" target="_blank" class="btn btn-primary">
                Lihat Hasil KGB
            </a>
        @endif

        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>
</div>
@endsection