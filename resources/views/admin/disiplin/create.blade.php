@extends('layouts.admin')

@section('title', 'Tambah Disiplin Pegawai')
@section('page_title', 'Tambah Disiplin Pegawai')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Data hukuman disiplin pegawai</h3>
    </div>

    <form method="POST" action="{{ route('admin.disiplin.store') }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Pilih pegawai</label>
                <select id="pegawai_id" name="pegawai_id" class="form-control select2 @error('pegawai_id') is-invalid @enderror" required>
                    <option value="">— Pilih —</option>
                    @foreach ($pegawais as $p)
                        <option value="{{ $p->id }}" @selected(old('pegawai_id') == $p->id)>
                            {{ $p->nip }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
                @error('pegawai_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>TMT berlaku</label>
                <input type="date" name="tmt_berlaku" value="{{ old('tmt_berlaku') }}" class="form-control @error('tmt_berlaku') is-invalid @enderror" required>
                @error('tmt_berlaku')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>TMT selesai (habis hukumannya kapan)</label>
                <input type="date" name="tmt_selesai" value="{{ old('tmt_selesai') }}" class="form-control @error('tmt_selesai') is-invalid @enderror">
                @error('tmt_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Tingkat hukuman</label>
                <select name="tingkat_hukuman" class="form-control @error('tingkat_hukuman') is-invalid @enderror" required>
                    <option value="">— Pilih —</option>
                    <option value="ringan" @selected(old('tingkat_hukuman') === 'ringan')>Ringan</option>
                    <option value="sedang" @selected(old('tingkat_hukuman') === 'sedang')>Sedang</option>
                    <option value="berat" @selected(old('tingkat_hukuman') === 'berat')>Berat</option>
                </select>
                @error('tingkat_hukuman')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Hukuman disiplin</label>
                <textarea name="hukuman_disiplin" class="form-control @error('hukuman_disiplin') is-invalid @enderror" rows="4" required>{{ old('hukuman_disiplin') }}</textarea>
                @error('hukuman_disiplin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-check">
                <input type="checkbox" name="selesai" value="1" id="selesai" class="form-check-input" {{ old('selesai') ? 'checked' : '' }}>
                <label for="selesai" class="form-check-label">Tandai selesai</label>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.disiplin.index') }}" class="btn btn-default">Batal</a>
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
        $('#pegawai_id').select2({
            width: '100%',
            placeholder: 'Cari NIP / Nama…'
        });
    });
</script>
@endpush

