<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SesiAbsensi;
class SesiAktifController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $sesi = \App\Models\SesiAbsensi::with(['jadwal.eskul'])
            ->where('status', 'aktif')
            ->whereHas('jadwal.eskul.anggotaEskul', function ($q) use ($user) {
                $q->where('siswa_id', $user->siswa->id)
                    ->where('status', 'aktif');
            })
            ->get();

        if ($sesi->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada sesi absensi aktif'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $sesi->map(function ($s) {
                return [
                    'sesi_absensi_id' => $s->id,
                    'tanggal' => $s->jadwal->tanggal,
                    'jam_mulai' => $s->jadwal->jam_mulai,
                    'jam_selesai' => $s->jadwal->jam_selesai,
                    'nama_eskul' => $s->jadwal->eskul->nama_eskul,
                    'mulai_absen' => $s->mulai_absen,
                    'selesai_absen' => $s->selesai_absen
                ];
            })
        ]);
    }
}