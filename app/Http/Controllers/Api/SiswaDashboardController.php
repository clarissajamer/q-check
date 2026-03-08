<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\QrTokenAbsensi;
use App\Models\AbsensiEskul;
use App\Models\SesiAbsensi;

class SiswaDashboardController extends Controller
{
    /**
     * Dashboard Siswa (Method index)
     */
    /**
     * Dashboard Siswa (Method index)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Validasi Relasi Siswa
        if (!$user->siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan untuk user ini.',
                'data' => null
            ], 404);
        }

        $siswa = $user->siswa;

        // 1. Data Profil (Nama & NIS)
        $profil = [
            'nama' => $siswa->nama,
            'nis' => $siswa->nis,
        ];

        // 2. Rekap Kehadiran
        // Menggunakan relasi 'absensiEskul' yang baru ditambahkan di model Siswa
        $rekapKehadiran = [
            'hadir' => $siswa->absensiEskul()->where('status', 'hadir')->count(),
            'izin'  => $siswa->absensiEskul()->whereIn('status', ['izin', 'sakit'])->count(), // Asumsi izin/sakit masuk kategori izin
            'alfa'  => $siswa->absensiEskul()->where('status', 'alfa')->count(),
        ];

        // 3. Daftar Eskul yang diikuti
        // Menggunakan relasi 'eskuls' (belongsToMany)
        $daftarEskul = $siswa->eskuls->map(function($eskul) {
            return [
                'id' => $eskul->id,
                'nama_eskul' => $eskul->nama_eskul,
            ];
        });
        
        // 4. Status Absen Hari Ini
        // Cek apakah ada sesi absensi yang aktif untuk eskul yang diikuti siswa hari ini
        $eskulIds = $siswa->eskuls->pluck('id');
        $sesiAktif = SesiAbsensi::whereHas('jadwal', function ($query) use ($eskulIds) {
            $query->whereIn('eskul_id', $eskulIds)
                  ->whereDate('tanggal', Carbon::today());
        })
        ->where('status', 'aktif') // Status aktif is 'aktif' based on previous code
        ->exists();

        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil dimuat',
            'data' => [
                'user' => $profil,
                'rekap_kehadiran' => $rekapKehadiran,
                'daftar_eskul' => $daftarEskul,
                'status_absen_hari_ini' => [
                    'ada_sesi_aktif' => $sesiAktif,
                    'pesan' => $sesiAktif ? 'Ada sesi absensi aktif hari ini.' : 'Tidak ada sesi absensi aktif hari ini.'
                ]
            ]
        ]);
    }

    /**
     * Scan QR Absensi (Method scanQr)
     */
    public function scanQr(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'message' => 'Format request tidak valid atau kosong.'
            ], 400);
        }

        $user = $request->user();

        // Cek Relasi User -> Siswa
        if (!$user->siswa) {
            return response()->json(['success' => false, 'message' => 'User tidak terhubung ke data siswa.'], 403);
        }
        $siswa = $user->siswa;

        // Cek Token
        $token = QrTokenAbsensi::where('token', $request->qr_token)->first();
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'QR tidak ditemukan'], 404);
        }
        if (Carbon::now()->greaterThan($token->expired_at)) {
            return response()->json(['success' => false, 'message' => 'QR sudah kedaluwarsa'], 410);
        }

        // Cek Sesi
        $sesi = $token->sesi;
        if (!$sesi || $sesi->status !== 'aktif') {
             if (!$sesi) return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan.'], 404);
             if ($sesi->status !== 'aktif' && $sesi->status !== 'open') {
                 return response()->json(['success' => false, 'message' => 'Sesi absensi sudah ditutup.'], 400);
             }
        }

        // Cek Enrollment Siswa di Eskul Sesi Ini
        $jadwal = $sesi->jadwal;
        if (!$jadwal) return response()->json(['success' => false, 'message' => 'Jadwal tidak valid atau terhapus.'], 500);

        // Gunakan relasi many-to-many check
        $isEnrolled = $siswa->eskuls()->where('eskul_id', $jadwal->eskul_id)->exists();
        if (!$isEnrolled) {
            return response()->json(['success' => false, 'message' => 'Anda bukan anggota eskul ini.'], 403);
        }

        // Menggunakan Atomic Lock untuk mencegah Race Condition (Double Hit Scan)
        $lockKey = 'scan_qr_sesi_' . $sesi->id . '_siswa_' . $siswa->id;
        $lock = Cache::lock($lockKey, 10); // Kunci proses selama maksimal 10 detik

        if (!$lock->get()) {
            return response()->json(['success' => false, 'message' => 'Sistem sedang memproses absensi Anda. Harap jangan menekan berulang kali.'], 429);
        }

        try {
            // Cek Duplikasi (Sudah absen?)
            $alreadyScan = AbsensiEskul::where('sesi_absensi_id', $sesi->id)
                                       ->where('siswa_id', $siswa->id)
                                       ->exists();
            
            if ($alreadyScan) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Anda sudah absen sebelumnya.'], 409);
            }

            // Simpan
            $absensi = AbsensiEskul::create([
                'sesi_absensi_id' => $sesi->id,
                'siswa_id' => $siswa->id,
                'status' => 'hadir',
                'waktu_scan' => Carbon::now(),
            ]);

            $lock->release();

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil',
                'data' => [
                    'waktu' => $absensi->created_at,
                    'status' => 'hadir',
                    'eskul' => $jadwal->eskul->nama_eskul ?? 'Eskul'
                ]
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            $lock->release();
            // 1062 adalah error code untuk Duplicate Entry MySQL
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'Anda sudah absen sebelumnya.'], 409);
            }
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat menyimpan.'], 500);
        } catch (\Exception $e) {
            $lock->release();
            // Catat log error asli di backend untuk debugging programmer, jangan kirim ke mobile
            // \Illuminate\Support\Facades\Log::error('QR Scan Error: ' . $e->getMessage()); 
            
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat menyimpan.'], 500);
        }
    }
}
