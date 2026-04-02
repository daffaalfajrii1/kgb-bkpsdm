<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HasilKgb;
use App\Models\Pengajuan;

class HomeController extends Controller
{
    public function index()
    {
        $totalPengajuan = Pengajuan::count();
        $totalDiproses = Pengajuan::where('status', 'diproses')->count();
        $totalSelesai = Pengajuan::where('status', 'selesai')->count();
        $latestResults = HasilKgb::latest()->take(6)->get();

        return view('public.home', compact(
            'totalPengajuan',
            'totalDiproses',
            'totalSelesai',
            'latestResults'
        ));
    }
}