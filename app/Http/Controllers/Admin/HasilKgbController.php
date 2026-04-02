<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKgb;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class HasilKgbController extends Controller
{
    public function index()
    {
        $items = HasilKgb::latest()->paginate(10);

        return view('admin.hasil-kgb.index', compact('items'));
    }

    public function create()
    {
        $pengajuans = Pengajuan::where('status', 'diproses')
            ->orderBy('nama')
            ->get();

        return view('admin.hasil-kgb.create', compact('pengajuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuans,id',
            'file_hasil' => 'required|mimes:pdf|max:2048',
            'tanggal_upload' => 'nullable|date',
        ]);

        $pengajuan = Pengajuan::findOrFail($request->pengajuan_id);

        $path = $request->file('file_hasil')->store('hasil-kgb', 'public');

        HasilKgb::updateOrCreate(
            ['pengajuan_id' => $pengajuan->id],
            [
                'nomor_registrasi' => $pengajuan->nomor_registrasi,
                'nama' => $pengajuan->nama,
                'nip' => $pengajuan->nip,
                'file_hasil' => $path,
                'tanggal_upload' => $request->tanggal_upload,
                'is_published' => true,
            ]
        );

        $pengajuan->update([
            'status' => 'selesai',
        ]);

        return redirect()->route('admin.hasil-kgb.index')
            ->with('success', 'File hasil KGB berhasil diupload.');
    }
}