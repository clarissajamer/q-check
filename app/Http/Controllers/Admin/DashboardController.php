<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eskul;
use App\Models\JadwalEskul;
use App\Models\SesiAbsensi;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalEskul'      => Eskul::count(),
            'jadwalHariIni'   => JadwalEskul::whereDate('tanggal', now())->count(),
            'absensiAktif'    => SesiAbsensi::where('status', 'dibuka')->exists(),
        ]);
    }
}
