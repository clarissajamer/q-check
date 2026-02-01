<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\SesiAbsensi;
use App\Models\AbsensiEskul;
use App\Models\Siswa;
use App\Helpers\GeoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function absen(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // 1️⃣ Ambil siswa
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        // 2️⃣ Cari sesi aktif + jadwal
        $sesi = SesiAbsensi::with('jadwal')
            ->where('status', 'aktif')
            ->first();

        if (!$sesi) {
            return back()->with('error', 'Tidak ada sesi absensi aktif');
        }

        $jadwal = $sesi->jadwal;

        // 3️⃣ Pastikan jadwal punya koordinat
        if (!$jadwal->latitude || !$jadwal->longitude) {
            return back()->with('error', 'Lokasi absensi belum ditentukan');
        }

        // 4️⃣ Cek sudah absen?
        $sudahAbsen = AbsensiEskul::where('sesi_absensi_id', $sesi->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Kamu sudah melakukan absensi');
        }

        // 5️⃣ HITUNG JARAK (INTI)
        $jarak = GeoHelper::distanceMeter(
            $request->latitude,
            $request->longitude,
            $jadwal->latitude,
            $jadwal->longitude
        );

        if ($jarak > $jadwal->radius_meter) {
            return back()->with('error', 
                'Kamu berada di luar radius absensi (' . round($jarak) . ' m)'
            );
        }

        // 6️⃣ SIMPAN ABSENSI
        AbsensiEskul::create([
            'sesi_absensi_id' => $sesi->id,
            'siswa_id'        => $siswa->id,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
            'status'          => 'hadir',
        ]);

        return back()->with('success', 'Absensi berhasil (jarak ' . round($jarak) . ' m)');
    }
}
