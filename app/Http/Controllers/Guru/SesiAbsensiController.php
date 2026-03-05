<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalEskul;
use App\Models\SesiAbsensi;
use App\Models\QrTokenAbsensi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SesiAbsensiController extends Controller
{
    public function buka(JadwalEskul $jadwal)
    {
        if ($jadwal->sesiAbsensi()->where('status', 'aktif')->exists()) {
            return back()->with('error', 'Sesi absensi masih aktif');
        }

        $sesi = SesiAbsensi::create([
            'jadwal_eskul_id' => $jadwal->id,
            'mulai_absen'     => now(),
            'dibuka_oleh'     => Auth::id(),
            'status'          => 'aktif',
        ]);

        QrTokenAbsensi::create([
            'sesi_absensi_id' => $sesi->id,
            'token'           => Str::random(40),
            'expired_at'      => Carbon::now()->addMinutes(2), 
        ]);

        return redirect()
            ->route('guru.sesi-absensi.qr', $sesi->id);
    }

    public function qr(SesiAbsensi $sesi)
    {

        $token = $sesi->qrToken()->latest()->first();

        if ($token && Carbon::now()->greaterThan($token->expired_at)) {
             $token = QrTokenAbsensi::create([
                'sesi_absensi_id' => $sesi->id,
                'token'           => Str::random(40),
                'expired_at'      => Carbon::now()->addMinutes(2),
            ]);
        }

        return view('guru.sesi.qr', compact('sesi', 'token'));
    }

    public function tutup(SesiAbsensi $sesi)
    {
        $sesi->update([
            'status' => 'selesai',
            'selesai_absen' => now(),
        ]);

        return redirect()->route('guru.jadwal.index')->with('success', 'Sesi absensi ditutup');
    }
}
