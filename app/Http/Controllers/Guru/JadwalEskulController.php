<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalEskul;
use Illuminate\Http\Request;

class JadwalEskulController extends Controller
{
    public function index()
    {
        // Menampilkan jadwal yang aktif dan belum selesai (opsional: filter hari ini)
        $jadwals = JadwalEskul::with('eskul')
            ->where('status', 'aktif')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.jadwal.index', compact('jadwals'));
    }
}
