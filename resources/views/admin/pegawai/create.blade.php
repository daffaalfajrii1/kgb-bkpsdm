@extends('layouts.admin')

@section('title', 'Tambah Pegawai')
@section('page_title', 'Tambah Pegawai')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Data pegawai baru</h3>
    </div>
    <form method="POST" action="{{ route('admin.pegawai.store') }}">
        @csrf
        <div class="card-body">
            <p class="text-muted">Password login otomatis sama dengan <strong>NIP</strong> (pegawai login: NIP + password NIP).</p>

            <div class="form-group">
                <label>NIP <span class="text-danger">*</span></label>
                <input type="text" name="nip" value="{{ old('nip') }}" class="form-control @error('nip') is-invalid @enderror" required>
                @error('nip')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Nama <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Email aktif <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Gol. / Pangkat</label>
                @php
                    $pangkatOptions = [
                        'I/a','I/b','I/c','I/d',
                        'II/a','II/b','II/c','II/d',
                        'III/a','III/b','III/c','III/d',
                        'IV/a','IV/b','IV/c','IV/d','IV/e',
                    ];
                @endphp
                <select name="gol_pangkat" class="form-control">
                    <option value="">— Pilih —</option>
                    @foreach ($pangkatOptions as $opt)
                        <option value="{{ $opt }}" @selected(old('gol_pangkat') === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>TMT Golongan</label>
                    <input type="date" name="tmt_golongan" value="{{ old('tmt_golongan') }}" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label>MK Tahun</label>
                    <input type="number" name="mk_tahun" value="{{ old('mk_tahun') }}" class="form-control" min="0" max="80">
                </div>
                <div class="form-group col-md-4">
                    <label>MK Bulan</label>
                    <input type="number" name="mk_bulan" value="{{ old('mk_bulan') }}" class="form-control" min="0" max="11">
                </div>
            </div>
            <div class="form-group">
                <label>TMT Jabatan</label>
                <input type="date" name="tmt_jabatan" value="{{ old('tmt_jabatan') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Unit kerja (dinas / instansi)</label>
                <input type="text" name="dinas_instansi" value="{{ old('dinas_instansi') }}" class="form-control">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-default">Batal</a>
        </div>
    </form>
</div>
@endsection
