@extends('layouts.admin')

@section('title', 'Manajemen Disiplin Pegawai')
@section('page_title', 'Manajemen Disiplin Pegawai')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title mb-0">Filter</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.disiplin.index') }}" class="form-row align-items-end">
            <div class="form-group col-md-4">
                <label>Cari (NIP / nama)</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Kata kunci">
            </div>
            <div class="form-group col-md-3">
                <label>Tingkat</label>
                <select name="tingkat" class="form-control">
                    <option value="">— Semua —</option>
                    <option value="ringan" @selected(request('tingkat') === 'ringan')>Ringan</option>
                    <option value="sedang" @selected(request('tingkat') === 'sedang')>Sedang</option>
                    <option value="berat" @selected(request('tingkat') === 'berat')>Berat</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>
            <div class="form-group col-md-3">
                <a href="{{ route('admin.disiplin.index') }}" class="btn btn-default">Reset</a>
                <a href="{{ route('admin.disiplin.create') }}" class="btn btn-success float-right">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Hukuman Disiplin</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover table-sm mb-0 table-text-center">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>TMT Berlaku</th>
                    <th>TMT Selesai</th>
                    <th>Selesai</th>
                    <th>Tingkat</th>
                    <th>Hukuman Disiplin</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    @php
                        $tingkat = $item->tingkat_hukuman;
                        $badge = match($tingkat) {
                            'berat' => 'badge badge-danger',
                            'sedang' => 'badge badge-warning',
                            default => 'badge badge-success',
                        };
                    @endphp
                    <tr>
                        <td>{{ $item->user?->name ?? '-' }}</td>
                        <td>{{ $item->user?->nip ?? '-' }}</td>
                        <td>{{ $item->tmt_berlaku ? $item->tmt_berlaku->format('d/m/Y') : '-' }}</td>
                        <td>{{ $item->tmt_selesai ? $item->tmt_selesai->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if ($item->selesai)
                                <span class="badge badge-secondary">Ya</span>
                            @else
                                <span class="badge badge-primary">Belum</span>
                            @endif
                        </td>
                        <td><span class="{{ $badge }}">{{ ucfirst($tingkat) }}</span></td>
                        <td class="text-left">{{ \Illuminate\Support\Str::limit($item->hukuman_disiplin, 120) }}</td>
                        <td>
                            <a href="{{ route('admin.disiplin.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.disiplin.destroy', $item->id) }}" class="d-inline" onsubmit="return confirm('Hapus data disiplin ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data disiplin.</td>
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

