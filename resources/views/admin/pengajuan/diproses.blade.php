@extends('layouts.admin')

@section('title', 'Data Diproses')
@section('page_title', 'Data Diproses')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-2 mb-md-0">Pengajuan Sedang Diproses</h3>

        <form method="GET" action="{{ route('admin.pengajuan.diproses') }}" class="d-flex flex-wrap">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control form-control-sm mr-2 mb-2"
                placeholder="Cari no reg / nama / NIP / instansi">
            <button class="btn btn-primary btn-sm mr-2 mb-2">Cari</button>
            <a href="{{ route('admin.pengajuan.diproses') }}" class="btn btn-secondary btn-sm mb-2">Reset</a>
        </form>
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
                    <th width="250">Aksi</th>
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
                            <a href="{{ route('admin.pengajuan.show', $item->id) }}" class="btn btn-info btn-sm">
                                Detail
                            </a>
                            <a href="{{ route('admin.hasil-kgb.create') }}" class="btn btn-success btn-sm">
                                Upload Hasil
                            </a>
                            <a href="{{ route('admin.pengajuan.show', $item->id) }}#formTolakPengajuan" class="btn btn-danger btn-sm">
                                Reject
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data diproses.</td>
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