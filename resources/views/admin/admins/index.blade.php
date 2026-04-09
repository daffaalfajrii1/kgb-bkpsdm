@extends('layouts.admin')

@section('title', 'Kelola Admin')
@section('page_title', 'Kelola Admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Admin</h3>

        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Admin
        </a>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover mb-0 table-text-center">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($admins as $admin)
                    <tr>
                        <td>{{ $admins->firstItem() + $loop->index }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.admins.destroy', $admin) }}"
                                style="display:inline-block;"
                                onsubmit="return confirm('Hapus admin ini?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada admin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $admins->links() }}
    </div>
</div>
@endsection

