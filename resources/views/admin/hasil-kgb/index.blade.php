@extends('layouts.admin')

@section('title', 'Upload Hasil KGB')
@section('page_title', 'Upload Hasil KGB')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Hasil KGB</h3>

        <a href="{{ route('admin.hasil-kgb.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Upload Hasil
        </a>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>No Registrasi</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Tanggal Upload</th>
                    <th width="120">File</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $items->firstItem() + $loop->index }}</td>
                        <td>{{ $item->nomor_registrasi ?? '-' }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->nip }}</td>
                        <td>
                            {{ $item->tanggal_upload ? \Carbon\Carbon::parse($item->tanggal_upload)->format('d-m-Y') : '-' }}
                        </td>
                        <td>
                            <a href="{{ asset('storage/'.$item->file_hasil) }}" target="_blank" class="btn btn-primary btn-sm">
                                Lihat PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada hasil KGB.</td>
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