<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\SesiAbsensi;
use App\Models\QrTokenAbsensi;
use App\Models\AbsensiEskul;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SiswaApiController extends Controller
{
    /**
     * Login Siswa
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // 1. Check User & Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // 2. Check Role Siswa
        if ($user->role !== 'siswa') {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun siswa',
            ], 403);
        }

        // 3. Check Relation Siswa
        if (!$user->siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan untuk akun ini',
            ], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'siswa' => [
                    'id' => $user->siswa->id,
                    'nis' => $user->siswa->nis,
                    'nama' => $user->siswa->nama,
                ],
            ],
        ]);
    }

    /**
     * Logout Siswa
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Dashboard Siswa
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        // Ambil daftar eskul yang diikuti siswa
        // Menggunakan relation 'eskuls' yang sudah didefinisikan di User/Siswa request (belongsToMany)
        // Pastikan model Siswa memiliki method eskuls()
        $eskuls = $siswa->eskuls()->get()->map(function ($eskul) {
            return [
                'id' => $eskul->id,
                'nama_eskul' => $eskul->nama_eskul,
                'status_keaktifan' => $eskul->pivot->status ?? 'unknown',
                'tahun_ajaran_id' => $eskul->pivot->tahun_ajaran_id ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil',
            'data' => [
                'profil' => [
                    'nis' => $siswa->nis,
                    'nama' => $siswa->nama,
                    'email' => $user->email,
                ],
                'eskul_diikuti' => $eskuls,
            ],
        ]);
    }

    /**
     * Scan QR Absensi (Fitur Inti)
     */
    public function scanQr(Request $request)
    {
        // Validasi Input
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $user = $request->user();
        $siswa = $user->siswa;
        
        // 1. User role & relation check sudah dihandle middleware, 
        // tapi kita pastikan siswa object ada.
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak valid.',
            ], 403);
        }

        $qrTokenString = $request->qr_token;

        // 2. Cari QR Token di database
        $qrToken = QrTokenAbsensi::where('token', $qrTokenString)->first();

        if (!$qrToken) {
            return response()->json([
                'success' => false,
                'message' => 'QR Token tidak valid.',
            ], 400); // Bad Request
        }

        // 3. Cek apakah token expired
        if (Carbon::now()->greaterThan($qrToken->expired_at)) {
            return response()->json([
                'success' => false,
                'message' => 'QR Token sudah kadaluarsa.',
            ], 400);
        }

        // 4. Ambil Sesi Absensi
        $sesi = $qrToken->sesi;
        if (!$sesi) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi absensi tidak ditemukan.',
            ], 404);
        }

        // 5. Cek Sesi Absensi Masih Aktif ?
        // Asumsi status 'aktif' atau 'open'. 
        // Jika tidak ada kolom status yang jelas, kita bisa cek waktu selesai.
        // Tapi user bilang "Sesi absensi masih aktif". Kita cek status & waktu.
        if ($sesi->status !== 'aktif' && $sesi->status !== 'open') {
             // Fallback cek waktu
             $now = Carbon::now();
             if ($sesi->selesai_absen && $now->greaterThan($sesi->selesai_absen)) {
                 return response()->json([
                    'success' => false,
                    'message' => 'Sesi absensi sudah ditutup.',
                 ], 400);
             }
             // Jika status explicitly closed/selesai
             if ($sesi->status == 'selesai' || $sesi->status == 'tutup') {
                 return response()->json([
                    'success' => false,
                    'message' => 'Sesi absensi sudah ditutup.',
                 ], 400);
             }
        }

        // 6. Cek Siswa Terdaftar di Eskul Sesi Ini
        // Sesi -> Jadwal -> Eskul
        $jadwal = $sesi->jadwal;
        if (!$jadwal) {
             return response()->json([
                'success' => false,
                'message' => 'Jadwal eskul tidak ditemukan.',
            ], 404);
        }
        
        $eskulId = $jadwal->eskul_id;

        // Cek di tabel anggota_eskul relationship
        // Apakah siswa ini anggota dari eskulId?
        $isMember = $siswa->eskuls()->where('eskul_id', $eskulId)->exists();

        // Atau bisa cek manual di tabel anggota_eskul jika relation tidak jalan
        // $isMember = \App\Models\AnggotaEskul::where('siswa_id', $siswa->id)->where('eskul_id', $eskulId)->exists();

        if (!$isMember) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak terdaftar di eskul ini. Silahkan hubungi pembina.',
            ], 403);
        }

        // 7. Cek Siswa Belum Pernah Absen di Sesi Ini
        $alreadyAbsent = AbsensiEskul::where('sesi_absensi_id', $sesi->id)
                                     ->where('siswa_id', $siswa->id)
                                     ->exists();

        if ($alreadyAbsent) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi di sesi ini.',
            ], 409); // Conflict
        }

        // 8. Simpan Absensi
        try {
            $absensi = AbsensiEskul::create([
                'sesi_absensi_id' => $sesi->id,
                'siswa_id' => $siswa->id,
                'status' => 'hadir', // Default hadir saat scan
                'latitude' => null, // Optional, user tidak minta kirim lokasi dari flutter
                'longitude' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil',
                'data' => [
                    'waktu_scan' => $absensi->created_at->format('Y-m-d H:i:s'),
                    'status' => $absensi->status,
                    'nama_eskul' => $jadwal->eskul->nama_eskul ?? 'Eskul', // Optional info
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan absensi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
