<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QrTokenAbsensi;
use App\Models\SesiAbsensi;
use App\Models\AbsensiEskul;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class QrAbsensiController extends Controller
{
    /**
     * Endpoint untuk scan QR absensi oleh siswa.
     */
    public function scanQr(Request $request)
    {
        Log::info('scanQr called', ['request' => $request->all()]);

        // 1. Validasi request
        $validator = Validator::make($request->all(), [
            'qr_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json([
                'success' => false,
                'message' => 'Format request tidak valid.',
                'errors' => $validator->errors()
            ], 400);
        }
        Log::info('Request valid', ['qr_token' => $request->qr_token]);

        // 2. Cek apakah user yang login adalah siswa
        $user = Auth::user();
        Log::info('Authenticated user', ['user_id' => $user?->id]);

        if (!$user) {
            Log::warning('Unauthorized access attempt');
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Silakan login kembali.',
            ], 401);
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            Log::warning('User bukan siswa', ['user_id' => $user->id]);
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya siswa yang dapat melakukan absensi.',
            ], 403);
        }
        Log::info('Siswa ditemukan', ['siswa_id' => $siswa->id]);

        // 3. Cari QR token di database
        $qrToken = QrTokenAbsensi::with('sesi')->where('token', $request->qr_token)->first();
        Log::info('QR token lookup', ['token' => $request->qr_token, 'found' => (bool)$qrToken]);

        if (!$qrToken) {
            return response()->json([
                'success' => false,
                'message' => 'QR token tidak valid atau tidak ditemukan di sistem.',
            ], 404);
        }

        // 4. Cek apakah QR token belum expired (menggunakan Carbon)
        $now = Carbon::now();
        Log::info('QR token expiration check', ['now' => $now, 'expired_at' => $qrToken->expired_at]);

        if ($now->greaterThan($qrToken->expired_at)) {
            return response()->json([
                'success' => false,
                'message' => 'QR token sudah expired. Silakan minta guru untuk refresh QR.',
            ], 410);
        }

        // 5. Cek apakah sesi_absensi status = aktif
        $sesi = $qrToken->sesi;
        Log::info('Sesi absensi', ['sesi_id' => $sesi?->id, 'status' => $sesi?->status]);

        if (!$sesi) {
            return response()->json([
                'success' => false,
                'message' => 'Data sesi absensi tidak ditemukan.',
            ], 404);
        }

        if ($sesi->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi absensi ini sudah selesai atau ditutup oleh guru.',
            ], 403);
        }

        // 6. Cek apakah siswa sudah absen di sesi tersebut (Anti double scan)
        $sudahAbsen = AbsensiEskul::where('sesi_absensi_id', $sesi->id)
            ->where('siswa_id', $siswa->id)
            ->exists();
        Log::info('Double scan check', ['siswa_id' => $siswa->id, 'sudah_absen' => $sudahAbsen]);

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi untuk sesi ini.',
            ], 409);
        }

        // 7. Insert ke absensi_eskul jika semua validasi berhasil
        try {
            $absensi = AbsensiEskul::create([
                'sesi_absensi_id' => $sesi->id,
                'siswa_id'        => $siswa->id,
                'status'          => 'hadir',
                'waktu_scan'      => now(),
            ]);
            Log::info('Absensi berhasil dicatat', ['absensi_id' => $absensi->id]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dicatat!',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error saat insert absensi', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan absensi.',
                'debug_error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}