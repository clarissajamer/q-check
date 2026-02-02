<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $sesiAktif = SesiAbsensi::where('status', 'aktif')
            ->latest()
            ->first();

        return view('siswa.dashboard', compact('sesiAktif'));
    }
}
