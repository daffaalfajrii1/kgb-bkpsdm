@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-4 col-12">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalPengajuan }}</h3>
                <p>Total Pengajuan</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-lines"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalDiproses }}</h3>
                <p>Sedang Diproses</p>
            </div>
            <div class="icon">
                <i class="fas fa-spinner"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalSelesai }}</h3>
                <p>Selesai</p>
            </div>
            <div class="icon">
                <i class="fas fa-circle-check"></i>
            </div>
        </div>
    </div>
</div>
@endsection