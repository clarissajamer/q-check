<?php

namespace App\Http\Controllers\Admin;

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
        $sekarang = Carbon::now();

        $mulai   = Carbon::parse($jadwal->jam_mulai);
        $selesai = Carbon::parse($jadwal->jam_selesai);

        // 1️⃣ cek jadwal belum mulai
        if ($sekarang->lt($mulai)) {
            return back()->with('error', 'Absensi belum bisa dibuka.');
        }

        // 2️⃣ cek jadwal sudah selesai
        if ($sekarang->gt($selesai)) {
            return back()->with('error', 'Waktu absensi sudah lewat.');
        }

        // 3️⃣ pastikan tidak ada sesi aktif
        if ($jadwal->sesiAbsensi()->where('status', 'aktif')->exists()) {
            return back()->with('error', 'Sesi absensi masih aktif.');
        }

        // 4️⃣ buat sesi absensi
        $sesi = SesiAbsensi::create([
            'jadwal_eskul_id' => $jadwal->id,
            'mulai_absen'     => now(),
            'dibuka_oleh'     => Auth::id(),
            'status'          => 'aktif',
        ]);

        // 5️⃣ buat QR token pertama
        QrTokenAbsensi::create([
            'sesi_absensi_id' => $sesi->id,
            'token'           => Str::random(40),
            'expired_at'      => Carbon::now()->addMinutes(2),
        ]);

        return redirect()->route('admin.sesi-absensi.qr', $sesi->id);
    }

    public function qr(SesiAbsensi $sesi)
    {
        $token = $sesi->qrToken()->latest()->first();

        return view('admin.sesi_absensi.qr', compact('sesi', 'token'));
    }

    public function tutup(SesiAbsensi $sesi)
    {
        $sesi->update([
            'status'        => 'selesai',
            'selesai_absen' => now(),
        ]);

        return redirect()->back()->with('success', 'Sesi absensi ditutup');
    }
}