@extends('layouts.admin')

@section('title', 'Edit SKP 2 Tahun Terakhir')
@section('page_title', 'Edit SKP 2 Tahun Terakhir')

@section('content')
@include('admin.skp._catatan')

<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title mb-0">Ubah data penilaian kinerja (SKP)</h3>
    </div>

    <form method="POST" action="{{ route('admin.pegawai-skp.update', $skp) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Pilih pegawai</label>
                <select id="user_id" name="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" required>
                    <option value="">— Pilih —</option>
                    @foreach ($pegawais as $p)
                        <option value="{{ $p->id }}" @selected(old('user_id', $skp->user_id) == $p->id)>
                            {{ $p->nip }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="alert alert-light border py-2 small text-muted mb-3">
                <strong class="text-secondary">Penilaian 2 tahun terakhir (otomatis):</strong>
                tahun <strong>{{ $tahunOtomatisBaru }}</strong> dan <strong>{{ $tahunOtomatisLama }}</strong>.
            </div>

            @if ($periodeTersimpanBerbeda)
                <div class="alert alert-warning py-2 small mb-3">
                    Data yang tersimpan memakai periode
                    <strong>{{ $skp->tahun_terbaru }} / {{ $skp->tahun_sebelumnya }}</strong>.
                    Setelah simpan, periode mengikuti periode otomatis di atas.
                </div>
            @endif

            @if ($predikatPerTahunBerbeda)
                <div class="alert alert-warning py-2 small mb-3">
                    Pada data lama, predikat per tahun berbeda
                    ({{ $skp->predikat_terbaru }} / {{ $skp->predikat_sebelumnya }}).
                    Pilih satu predikat untuk keduanya.
                </div>
            @endif

            <div class="form-group">
                <label>Predikat 2 tahun terakhir</label>
                <select name="predikat_2_tahun" class="form-control @error('predikat_2_tahun') is-invalid @enderror" required>
                    <option value="">— Pilih predikat —</option>
                    @foreach ($predikatOpsi as $opt)
                        <option value="{{ $opt }}" @selected(old('predikat_2_tahun', $predikatDefaultForm) === $opt)>{{ $opt }}</option>
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
