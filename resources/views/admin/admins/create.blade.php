@extends('layouts.admin')

@section('title', 'Tambah Admin')
@section('page_title', 'Tambah Admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Admin</h3>
    </div>

    <form action="{{ route('admin.admins.store') }}" method="POST">
        @csrf

        <div class="card-body">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-primary" type="submit">
                Simpan
            </button>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection

