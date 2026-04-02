@extends('layouts.admin')

@section('title', 'Tambah Upload Hasil KGB')
@section('page_title', 'Tambah Upload Hasil KGB')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Upload Hasil KGB</h3>
    </div>

    <form action="{{ route('admin.hasil-kgb.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            <div class="form-group">
                <label>Pilih Nomor Registrasi Pengajuan Diproses</label>
                <select name="pengajuan_id" id="pengajuan_id" class="form-control" required>
                    <option value="">-- Pilih Nomor Registrasi --</option>
                    @foreach ($pengajuans as $pengajuan)
                        <option
                            value="{{ $pengajuan->id }}"
                            data-nomor="{{ $pengajuan->nomor_registrasi }}"
                            data-nama="{{ $pengajuan->nama }}"
                            data-nip="{{ $pengajuan->nip }}"
                            data-instansi="{{ $pengajuan->dinas_instansi }}"
                            {{ old('pengajuan_id') == $pengajuan->id ? 'selected' : '' }}>
                            {{ $pengajuan->nomor_registrasi }}
                        </option>
                    @endforeach
                </select>
                @error('pengajuan_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" id="nama_preview" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>NIP</label>
                <input type="text" id="nip_preview" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Dinas / Instansi</label>
                <input type="text" id="instansi_preview" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Tanggal Upload</label>
                <input
                    type="date"
                    name="tanggal_upload"
                    class="form-control"
                    value="{{ old('tanggal_upload', date('Y-m-d')) }}">
                @error('tanggal_upload')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>File Hasil KGB (PDF)</label>
                <input type="file" name="file_hasil" class="form-control" accept="application/pdf" required>
                @error('file_hasil')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            @if($pengajuans->isEmpty())
                <div class="alert alert-warning mb-0">
                    Tidak ada pengajuan dengan status diproses. Tandai dulu data pengajuan ke status <strong>diproses</strong>.
                </div>
            @endif
        </div>

        <div class="card-footer">
            <button class="btn btn-primary" {{ $pengajuans->isEmpty() ? 'disabled' : '' }}>
                Simpan
            </button>
            <a href="{{ route('admin.hasil-kgb.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectPengajuan = document.getElementById('pengajuan_id');
    const namaPreview = document.getElementById('nama_preview');
    const nipPreview = document.getElementById('nip_preview');
    const instansiPreview = document.getElementById('instansi_preview');

    function updatePreview() {
        const selected = selectPengajuan.options[selectPengajuan.selectedIndex];

        namaPreview.value = selected ? (selected.getAttribute('data-nama') || '') : '';
        nipPreview.value = selected ? (selected.getAttribute('data-nip') || '') : '';
        instansiPreview.value = selected ? (selected.getAttribute('data-instansi') || '') : '';
    }

    selectPengajuan.addEventListener('change', updatePreview);
    updatePreview();
});
</script>
@endsection