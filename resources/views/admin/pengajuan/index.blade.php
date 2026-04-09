@extends('layouts.admin')

@section('title', 'Data Pengajuan')
@section('page_title', 'Data Pengajuan')

@section('content')
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $countSemua }}</h3>
                <p>Semua Pengajuan</p>
            </div>
            <div class="icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <a href="{{ route('admin.pengajuan.index') }}" class="small-box-footer">
                Lihat Semua <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $countDiajukan }}</h3>
                <p>Diajukan</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-circle-plus"></i>
            </div>
            <a href="{{ route('admin.pengajuan.index', ['status' => 'diajukan']) }}" class="small-box-footer">
                Filter Diajukan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $countDiproses }}</h3>
                <p>Diproses</p>
            </div>
            <div class="icon">
                <i class="fas fa-spinner"></i>
            </div>
            <a href="{{ route('admin.pengajuan.index', ['status' => 'diproses']) }}" class="small-box-footer">
                Filter Diproses <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $countSelesai }}</h3>
                <p>Selesai</p>
            </div>
            <div class="icon">
                <i class="fas fa-circle-check"></i>
            </div>
            <a href="{{ route('admin.pengajuan.index', ['status' => 'selesai']) }}" class="small-box-footer">
                Filter Selesai <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="card-title mb-2 mb-md-0">Semua Data Pengajuan</h3>

            <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="d-flex flex-wrap">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control form-control-sm mr-2 mb-2"
                    placeholder="Cari no reg / nama / NIP / instansi">

                <select name="status" class="form-control form-control-sm mr-2 mb-2">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

                <button class="btn btn-primary btn-sm mr-2 mb-2">Filter</button>

                <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary btn-sm mb-2">
                    Reset
                </a>
            </form>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover mb-0 table-text-center">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>No Registrasi</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Dinas/Instansi</th>
                    <th>Pangkat Terakhir</th>
                    <th>TMT Berkala Berikutnya</th>
                    <th>Status</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajuans as $item)
                    <tr>
                        <td>{{ $pengajuans->firstItem() + $loop->index }}</td>
                        <td>{{ $item->nomor_registrasi }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->dinas_instansi }}</td>
                        <td>{{ $item->pangkat_terakhir }}</td>
                        <td>
                            {{ $item->tmt_berkala_berikutnya ? \Carbon\Carbon::parse($item->tmt_berkala_berikutnya)->format('d-m-Y') : '-' }}
                        </td>
                        <td>
                            @if ($item->status === 'diajukan')
                                <span class="badge badge-secondary">Diajukan</span>
                            @elseif ($item->status === 'diproses')
                                <span class="badge badge-warning">Diproses</span>
                            @elseif ($item->status === 'ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                            @elseif ($item->status === 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-dark">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.pengajuan.show', $item->id) }}" class="btn btn-info btn-sm">
                                Detail
                            </a>

                            @if ($item->status === 'diajukan')
                                <form action="{{ route('admin.pengajuan.proses', $item->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-warning btn-sm" onclick="return confirm('Tandai pengajuan ini sebagai diproses?')">
                                        Proses
                                    </button>
                                </form>
                            @endif

                            @if ($item->status === 'diproses')
                                <a href="{{ route('admin.hasil-kgb.create') }}" class="btn btn-success btn-sm">
                                    Upload Hasil
                                </a>
                            @endif

                            @if ($item->status === 'selesai')
                                <span class="text-success font-weight-bold">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data pengajuan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $pengajuans->withQueryString()->links() }}
    </div>
</div>
@endsection