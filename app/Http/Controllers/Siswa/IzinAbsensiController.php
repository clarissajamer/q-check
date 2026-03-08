<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IzinAbsensi;
use App\Models\AbsensiEskul;

class IzinAbsensiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sesi_absensi_id' => 'required|exists:sesi_absensi,id',
            'jenis_izin' => 'required|in:izin,sakit',
            'alasan' => 'nullable|string',
            'bukti_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = auth()->user();
        $siswa = $user->siswa()->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        // cek apakah sudah absen
        $sudahAbsen = AbsensiEskul::where('siswa_id', $siswa->id)
            ->where('sesi_absensi_id', $request->sesi_absensi_id)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi'
            ], 400);
        }

        // cek apakah sudah ajukan izin
        $sudahIzin = IzinAbsensi::where('siswa_id', $siswa->id)
            ->where('sesi_absensi_id', $request->sesi_absensi_id)
            ->exists();

        if ($sudahIzin) {
            return response()->json([
                'success' => false,
                'message' => 'Izin sudah diajukan'
            ], 400);
        }

        $filePath = null;

        if ($request->hasFile('bukti_file')) {
            $filePath = $request->file('bukti_file')->store('bukti_izin', 'public');
        }

        $izin = IzinAbsensi::create([
            'siswa_id' => $siswa->id,
            'sesi_absensi_id' => $request->sesi_absensi_id,
            'jenis_izin' => $request->jenis_izin,
            'alasan' => $request->alasan,
            'bukti_file' => $filePath,
            'status' => 'pending',
            'tanggal_pengajuan' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin berhasil',
            'data' => $izin
        ]);
    }

    public function index()
    {
        $user = auth()->user();
        $siswa = $user->siswa;

        $izin = IzinAbsensi::where('siswa_id', $siswa->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $izin
        ]);
    }

    public function show($id)
{
    $user = auth()->user();

    if (!$user->siswa) {
        return response()->json([
            'success' => false,
            'message' => 'Data siswa tidak ditemukan'
        ], 404);
    }

    $siswa = $user->siswa;

    $izin = IzinAbsensi::with([
        'sesiAbsensi.jadwalEskul.eskul'
    ])
    ->where('id', $id)
    ->where('siswa_id', $siswa->id)
    ->first();

    if (!$izin) {
        return response()->json([
            'success' => false,
            'message' => 'Data izin tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $izin->id,
            'jenis_izin' => $izin->jenis_izin,
            'alasan' => $izin->alasan,
            'status' => $izin->status,
            'tanggal_pengajuan' => $izin->tanggal_pengajuan,
            'bukti_file' => $izin->bukti_file,
            'tanggal_kegiatan' => $izin->sesiAbsensi->jadwalEskul->tanggal,
            'nama_eskul' => $izin->sesiAbsensi->jadwalEskul->eskul->nama_eskul
        ]
    ]);
}
}