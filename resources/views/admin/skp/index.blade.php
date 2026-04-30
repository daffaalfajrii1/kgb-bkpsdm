@extends('layouts.admin')

@section('title', 'Manajemen SKP (1 Tahun Terakhir)')
@section('page_title', 'Manajemen SKP (1 Tahun Terakhir)')

@section('content')
@include('admin.skp._catatan')

<div class="alert alert-light border small text-muted mb-3 py-2">
    <strong class="text-secondary">Periode otomatis saat ini:</strong>
    tahun <strong>{{ $tAutoBaru }}</strong>.
</div>

<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title mb-0">Filter</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pegawai-skp.index') }}" class="form-row align-items-end">
            <div class="form-group col-md-6">
                <label>Cari (NIP / nama)</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Kata kunci">
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>
            <div class="form-group col-md-4 text-right">
                <a href="{{ route('admin.pegawai-skp.index') }}" class="btn btn-default">Reset</a>
                <a href="{{ route('admin.pegawai-skp.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Daftar SKP per pegawai</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover table-sm mb-0 table-text-center">
            <thead class="thead-light">
                <tr>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Penilaian 1 tahun terakhir<br><span class="small font-weight-normal">(tahun tersimpan)</span></th>
                    <th>Predikat 1 tahun terakhir</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    @php
                        $buruk = \App\Services\PegawaiAksesDisiplinService::skpMemblokirAkses($item);
                        $p1 = trim((string) $item->predikat_terbaru);
                    @endphp
                    <tr class="{{ $buruk ? 'table-warning' : '' }}">
                        <td>{{ $item->user?->name ?? '-' }}</td>
                        <td>{{ $item->user?->nip ?? '-' }}</td>
                        <td class="text-nowrap">{{ $item->tahun_terbaru }}</td>
                        <td>{{ $p1 }}</td>
                        <td>
                            <a href="{{ route('admin.pegawai-skp.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.pegawai-skp.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus data SKP pegawai ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data SKP. Gunakan tombol &ldquo;Tambah&rdquo;.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $items->links() }}
    </div>
</div>
@endsection
