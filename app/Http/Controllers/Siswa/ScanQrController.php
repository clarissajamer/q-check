<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\QrTokenAbsensi;
use App\Models\AbsensiEskul;
use App\Models\Siswa;
use App\Helpers\GeoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScanQrController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'token'     => 'required',
            'latitude'  => 'required|numeric',
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

        // 3️⃣ Hitung jarak
        $jarak = GeoHelper::distanceMeter(
            $request->latitude,
            $request->longitude,
            $jadwal->latitude,
            $jadwal->longitude
        );

        if ($jarak > $jadwal->radius_meter) {
            return back()->with('error', 'Di luar radius absensi');
        }

        // 4️⃣ Simpan absensi
        AbsensiEskul::create([
            'sesi_absensi_id' => $sesi->id,
            'siswa_id'        => $siswa->id,
            'status'          => 'hadir',
        ]);

        // 5️⃣ HAPUS TOKEN (ANTI TITIP)
        $qr->delete();

        return back()->with('success', 'Absensi berhasil');
    }
}
