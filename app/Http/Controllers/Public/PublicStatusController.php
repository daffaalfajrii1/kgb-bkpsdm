<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HasilKgb;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PublicStatusController extends Controller
{
    public function index(Request $request)
    {
        return view('public.status.index', [
            'prefill' => $request->get('nomor_registrasi', ''),
            'pengajuan' => null,
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
        ]);

        $keyword = $request->keyword;

        $pengajuan = Pengajuan::with('hasilKgb')
            ->where('nomor_registrasi', $keyword)
            ->orWhere('nip', $keyword)
            ->orWhere('nama', 'like', '%'.$keyword.'%')
            ->first();

        return view('public.status.index', [
            'prefill' => $keyword,
            'pengajuan' => $pengajuan,
        ]);
    }

    public function skIndex(Request $request)
    {
        $query = HasilKgb::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where('nomor_registrasi', $keyword)
                ->orWhere('nama', 'like', '%'.$keyword.'%');
        }

        $items = $query->latest()->paginate(10);

        return view('public.sk.index', compact('items'));
    }

    public function download(HasilKgb $hasilKgb)
    {
        return response()->download(storage_path('app/public/' . $hasilKgb->file_hasil));
    }
}