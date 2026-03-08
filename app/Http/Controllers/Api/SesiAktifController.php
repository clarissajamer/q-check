<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SesiAbsensi;

class SesiAktifController extends Controller
{
    public function index()
    {
        $sesi = SesiAbsensi::with([
            'jadwalEskul.eskul'
        ])
        ->where('status', 'aktif')
        ->first();

        if (!$sesi) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada sesi absensi aktif'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'sesi_absensi_id' => $sesi->id,
                'tanggal' => $sesi->jadwalEskul->tanggal,
                'jam_mulai' => $sesi->jadwalEskul->jam_mulai,
                'jam_selesai' => $sesi->jadwalEskul->jam_selesai,
                'nama_eskul' => $sesi->jadwalEskul->eskul->nama_eskul,
                'mulai_absen' => $sesi->mulai_absen,
                'selesai_absen' => $sesi->selesai_absen
            ]
        ]);
    }
}