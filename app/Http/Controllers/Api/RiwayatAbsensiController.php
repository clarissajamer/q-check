<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiEskul;

class RiwayatAbsensiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        $siswa = $user->siswa;

        $riwayat = AbsensiEskul::with([
            'sesiAbsensi.jadwalEskul.eskul'
        ])
        ->where('siswa_id', $siswa->id)
        ->orderBy('waktu_scan', 'desc')
        ->get()
        ->map(function ($item) {
            return [
                'tanggal' => $item->sesiAbsensi->jadwalEskul->tanggal,
                'nama_eskul' => $item->sesiAbsensi->jadwalEskul->eskul->nama_eskul,
                'status' => $item->status,
                'waktu_scan' => $item->waktu_scan
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
    }
}