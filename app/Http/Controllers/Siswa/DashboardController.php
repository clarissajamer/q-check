<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\SesiAbsensi;

class DashboardController extends Controller
{
    public function index()
    {
        $sesiAktif = SesiAbsensi::where('status', 'aktif')
            ->latest()
            ->first();

        return view('siswa.dashboard', compact('sesiAktif'));
    }
}
