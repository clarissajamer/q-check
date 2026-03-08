<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiEskul;
use App\Models\IzinAbsensi;
use Illuminate\Support\Collection;

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

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Ambil Data Hadir (Scan QR)
        |--------------------------------------------------------------------------
        */
        $absensi = AbsensiEskul::with('sesiAbsensi.jadwal.eskul')
            ->where('siswa_id', $siswa->id)
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->sesiAbsensi->jadwal->tanggal,
                    'nama_eskul' => $item->sesiAbsensi->jadwal->eskul->nama_eskul,
                    'status' => 'hadir',
                    'waktu' => $item->waktu_scan,
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Ambil Data Izin & Sakit
        |--------------------------------------------------------------------------
        */
        $izin = IzinAbsensi::with('sesiAbsensi.jadwal.eskul')
            ->where('siswa_id', $siswa->id)
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->sesiAbsensi->jadwal->tanggal,
                    'nama_eskul' => $item->sesiAbsensi->jadwal->eskul->nama_eskul,
                    'status' => $item->jenis_izin, // izin / sakit
                    'waktu' => $item->tanggal_pengajuan,
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Gabungkan & Urutkan
        |--------------------------------------------------------------------------
        */
        $riwayat = collect()
            ->merge($absensi)
            ->merge($izin)
            ->sortByDesc('tanggal')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
    }
}