<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function dashboard()
    {
        $totalPengajuan = Pengajuan::count();
        $totalDiproses = Pengajuan::where('status', 'diproses')->count();
        $totalSelesai = Pengajuan::where('status', 'selesai')->count();

        return view('admin.dashboard', compact(
            'totalPengajuan',
            'totalDiproses',
            'totalSelesai'
        ));
    }

    public function index(Request $request)
    {
        $query = Pengajuan::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('dinas_instansi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['diajukan', 'diproses', 'selesai'])) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);

        $countSemua = Pengajuan::count();
        $countDiajukan = Pengajuan::where('status', 'diajukan')->count();
        $countDiproses = Pengajuan::where('status', 'diproses')->count();
        $countSelesai = Pengajuan::where('status', 'selesai')->count();

        return view('admin.pengajuan.index', compact(
            'pengajuans',
            'countSemua',
            'countDiajukan',
            'countDiproses',
            'countSelesai'
        ));
    }

    public function diproses(Request $request)
    {
        $query = Pengajuan::where('status', 'diproses');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('dinas_instansi', 'like', "%{$search}%");
            });
        }

        $pengajuans = $query->latest()->paginate(10);

        return view('admin.pengajuan.diproses', compact('pengajuans'));
    }

    public function selesaiIndex(Request $request)
    {
        $query = Pengajuan::with('hasilKgb')->where('status', 'selesai');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('dinas_instansi', 'like', "%{$search}%");
            });
        }

        $pengajuans = $query->latest()->paginate(10);

        return view('admin.pengajuan.selesai', compact('pengajuans'));
    }

    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load('hasilKgb');

        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    public function proses(Pengajuan $pengajuan)
    {
        $pengajuan->update([
            'status' => 'diproses',
        ]);

        return back()->with('success', 'Pengajuan berhasil ditandai sebagai diproses.');
    }

    public function selesai(Pengajuan $pengajuan)
    {
        $pengajuan->update([
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Pengajuan berhasil ditandai sebagai selesai.');
    }
}