<?php

namespace App\Http\Controllers\Siswa;

use App;
use App\Http\Controllers\Controller;
use App\Models\QrTokenAbsensi;
use App\Models\AbsensiEskul;
use App\Models\Siswa;
use App\Helpers\GeoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AnggotaEskul;

class ScanQrController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // 1️⃣ Ambil token valid
        $qr = QrTokenAbsensi::where('token', $request->token)
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if (!$qr) {
            return back()->with('error', 'QR tidak valid / sudah expired');
        }

        $sesi = $qr->sesi;
        $jadwal = $sesi->jadwal;


        // 2️⃣ Ambil siswa
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        // 🔒 Cek apakah siswa anggota ekskul ini
        $anggota = AnggotaEskul::where('siswa_id', $siswa->id)
            ->where('eskul_id', $jadwal->eskul_id)
            ->where('status', 'aktif')
            ->exists();

        if (!$anggota) {
            return back()->with('error', 'Anda bukan anggota ekskul ini');
        }


        // 3 Ambil siswa
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        // Cek double absen
        $sudahAbsen = AbsensiEskul::where('sesi_absensi_id', $sesi->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Anda sudah absen');
        }

        // 4 Hitung jarak
        $jarak = GeoHelper::distanceMeter(
            $jadwal->latitude,
            $jadwal->longitude,
            $request->latitude,
            $request->longitude
        );

        if ($jarak > $jadwal->radius_meter) {
            return back()->with('error', 'Di luar radius absensi');
        }

        // 5 Simpan absensi
        AbsensiEskul::create([
            'sesi_absensi_id' => $sesi->id,
            'siswa_id' => $siswa->id,
            'status' => 'hadir',
        ]);

        // 6 HAPUS TOKEN (ANTI TITIP)
        return back()->with('success', 'Absensi berhasil');

    }
}
