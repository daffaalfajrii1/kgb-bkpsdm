<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PegawaiDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('pegawai')->user();

        return view('pegawai.dashboard', compact('user'));
    }
}

