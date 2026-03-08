<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\SesiAbsensi;
use App\Models\AbsensiEskul;
use App\Models\Siswa;
use App\Helpers\GeoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function absen(Request $request)
    {
        $request->validate([
            'sesi_absensi_id' => 'required|exists:sesi_absensi,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $sesi = SesiAbsensi::with('jadwal')
            ->where('id', $request->sesi_absensi_id)
            ->where('status', 'aktif')
            ->first();

        if (!$sesi) {
            return back()->with('error', 'Sesi absensi tidak aktif');
        }

        $jadwal = $sesi->jadwalEskul;

        if (!$jadwal->latitude || !$jadwal->longitude) {
            return back()->with('error', 'Lokasi jadwal belum ditentukan');
        }

        // hitung jarak
        $jarak = GeoHelper::distanceMeter(
            $jadwal->latitude,
            $jadwal->longitude,
            $request->latitude,
            $request->longitude
        );

        if ($jarak > $jadwal->radius_meter) {
            return back()->with(
                'error',
                'Anda berada di luar radius (' . round($jarak) . ' m)'
            );
        }

        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        // cegah double absen
        $sudahAbsen = AbsensiEskul::where('sesi_absensi_id', $sesi->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Anda sudah absen');
        }

        AbsensiEskul::create([
            'id' => (string) Str::uuid(),
            'sesi_absensi_id' => $sesi->id,
            'siswa_id' => $siswa->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'jarak_meter' => round($jarak),
            'status' => 'hadir',
        ]);

        return back()->with('success', 'Absensi berhasil');
    }
}
