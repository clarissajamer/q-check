<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SesiAbsensi;
use App\Models\AbsensiEskul;
use App\Models\IzinAbsensi;

class StatusAbsensiController extends Controller
{
    public function status(Request $request)
    {
        $user = auth()->user();

        if (!$user->siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        $siswa = $user->siswa;

        $sesi = SesiAbsensi::where('status', 'aktif')->first();

        if (!$sesi) {
            return response()->json([
                'success' => true,
                'status' => 'tidak_ada_sesi'
            ]);
        }

        $absen = AbsensiEskul::where('siswa_id', $siswa->id)
            ->where('sesi_absensi_id', $sesi->id)
            ->first();

        if ($absen) {
            return response()->json([
                'success' => true,
                'status' => 'hadir',
                'sesi_absensi_id' => $sesi->id
            ]);
        }

        $izin = IzinAbsensi::where('siswa_id', $siswa->id)
            ->where('sesi_absensi_id', $sesi->id)
            ->first();

        if ($izin) {
            return response()->json([
                'success' => true,
                'status' => 'izin_' . $izin->status,
                'sesi_absensi_id' => $sesi->id
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 'belum_absen',
            'sesi_absensi_id' => $sesi->id
        ]);
    }
}