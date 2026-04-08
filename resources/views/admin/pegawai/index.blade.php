@extends('layouts.admin')

@section('title', 'Manajemen Pegawai')
@section('page_title', 'Manajemen Pegawai')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title mb-0">Filter &amp; Impor</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pegawai.index') }}" class="form-row align-items-end">
            <div class="form-group col-md-3">
                <label>Cari (NIP / nama / email)</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Kata kunci">
            </div>
            <div class="form-group col-md-4">
                <label>Unit kerja (instansi)</label>
                <select name="instansi" class="form-control">
                    <option value="">— Semua —</option>
                    @foreach ($instansiList as $ins)
                        <option value="{{ $ins }}" @selected(request('instansi') === $ins)>{{ $ins }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Urutkan</label>
                <select name="sort" class="form-control">
                    <option value="instansi" @selected(request('sort', 'instansi') === 'instansi')>Instansi, lalu nama</option>
                    <option value="nip" @selected(request('sort') === 'nip')>NIP</option>
                    <option value="nama" @selected(request('sort') === 'nama')>Nama</option>
                    <option value="pangkat_desc" @selected(request('sort') === 'pangkat_desc')>Pangkat tertinggi → terendah</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <button type="submit" class="btn btn-primary mr-2">Terapkan</button>
                <a href="{{ route('admin.pegawai.index') }}" class="btn btn-default">Reset</a>
            </div>
        </form>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <p class="text-muted small mb-2">
                    Impor Excel (.xlsx) dengan header: NIP, NAMA, EMAIL, GOL./PANGKAT, TMT GOLONGAN, MK TAHUN, MK BULAN, TMT JABATAN, UNIT KERJA.
                    Password akun otomatis = NIP (plain). 
                </p>
                <a href="{{ route('admin.pegawai.template') }}" class="btn btn-outline-secondary btn-sm mb-2">
                    <i class="fas fa-download"></i> Unduh template Excel
                </a>
                <form method="POST" action="{{ route('admin.pegawai.import') }}" enctype="multipart/form-data" class="mt-2">
                    @csrf
                    @error('impor')
                        <div class="alert alert-danger py-2">{{ $message }}</div>
                    @enderror
                    <div class="form-group">
                        <input type="file" name="file" accept=".xlsx,.xls" class="form-control-file" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-file-upload"></i> Impor Excel
                    </button>
                </form>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah pegawai manual
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Daftar Pegawai ({{ $pegawais->total() }})</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover table-sm mb-0">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Gol./Pangkat</th>
                    <th>TMT</th>
                    <th>MK</th>
                    <th>TMT Jabatan</th>
                    <th>Unit kerja</th>
                    <th width="260">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pegawais as $p)
                    <tr>
                        <td>{{ $pegawais->firstItem() + $loop->index }}</td>
                        <td>{{ $p->nip }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->gol_pangkat ?? $p->pangkat_terakhir ?? '-' }}</td>
                        <td>{{ $p->tmt_golongan?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            @if ($p->mk_tahun !== null || $p->mk_bulan !== null)
                                {{ (int) ($p->mk_tahun ?? 0) }} th {{ (int) ($p->mk_bulan ?? 0) }} bln
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $p->tmt_jabatan?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $p->dinas_instansi ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.pegawai.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.pegawai.reset-password', $p->id) }}" class="d-inline" onsubmit="return confirm('Reset password menjadi NIP untuk pegawai ini?');">
                                @csrf
                                <button type="submit" class="btn btn-info btn-sm">Reset PW</button>
                            </form>
                            <form method="POST" action="{{ route('admin.pegawai.destroy', $p->id) }}" class="d-inline" onsubmit="return confirm('Hapus pegawai ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Belum ada data pegawai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $pegawais->links() }}
    </div>
</div>
@endsection
