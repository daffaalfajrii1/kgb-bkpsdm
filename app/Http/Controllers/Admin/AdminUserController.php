<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::query()
            ->where('role', 'admin')
            ->latest()
            ->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'admin',
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin baru berhasil ditambahkan.');
    }

    public function edit(User $admin)
    {
        abort_if($admin->role !== 'admin', 403, 'Akses hanya untuk admin.');

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        abort_if($admin->role !== 'admin', 403, 'Akses hanya untuk admin.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $admin->id],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $validated['password'] = $request->input('password');
        }

        $admin->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'admin',
        ]);

        if (!empty($validated['password'])) {
            $admin->password = bcrypt($validated['password']);
        }

        $admin->save();

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        abort_if($admin->role !== 'admin', 403, 'Akses hanya untuk admin.');

        $totalAdmins = User::where('role', 'admin')->count();
        if ($totalAdmins <= 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir.');
        }

        $admin->delete();

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}

