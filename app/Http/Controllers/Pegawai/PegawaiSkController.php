<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\HasilKgb;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PegawaiSkController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('pegawai')->user();

        $items = HasilKgb::where('nip', $user->nip)
            ->where('is_published', true)
            ->latest()
            ->paginate(10);

        return view('pegawai.sk.index', compact('items', 'user'));
    }
}

