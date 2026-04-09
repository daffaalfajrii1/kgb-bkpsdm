@extends('layouts.admin')

@section('title', 'Tambah SKP 2 Tahun Terakhir')
@section('page_title', 'Tambah SKP 2 Tahun Terakhir')

@section('content')
@include('admin.skp._catatan')

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title mb-0">Data penilaian kinerja (SKP)</h3>
    </div>

    <form method="POST" action="{{ route('admin.pegawai-skp.store') }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Pilih pegawai</label>
                <select id="user_id" name="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" required>
                    <option value="">— Pilih —</option>
                    @foreach ($pegawais as $p)
                        <option value="{{ $p->id }}" @selected(old('user_id') == $p->id)>
                            {{ $p->nip }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="alert alert-light border mb-3 py-2 small text-muted">
                <strong class="text-secondary">Penilaian 2 tahun terakhir (otomatis):</strong>
                tahun <strong>{{ $tahunOtomatisBaru }}</strong> dan <strong>{{ $tahunOtomatisLama }}</strong>.
            </div>

            <div class="form-group">
                <label>Predikat 2 tahun terakhir</label>
                <select name="predikat_2_tahun" class="form-control @error('predikat_2_tahun') is-invalid @enderror" required>
                    <option value="">— Pilih predikat —</option>
                    @foreach ($predikatOpsi as $opt)
                        <option value="{{ $opt }}" @selected(old('predikat_2_tahun') === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('predikat_2_tahun')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <small class="form-text text-muted">Pilihan: Baik, Buruk, Sangat Buruk — berlaku untuk kedua tahun dalam periode tersebut.</small>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.pegawai-skp.index') }}" class="btn btn-default">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function () {
        $('#user_id').select2({ width: '100%', placeholder: 'Cari NIP / Nama…' });
    });
</script>
@endpush
