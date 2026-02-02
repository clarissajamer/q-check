<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalEskul;
use App\Models\Eskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalEskulController extends Controller
{
    public function index()
    {
        $jadwals = JadwalEskul::with('eskul')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.jadwal_eskul.index', compact('jadwals'));
    }

    public function create()
    {
        $eskuls = Eskul::orderBy('nama_eskul')->get();
        return view('admin.jadwal_eskul.create', compact('eskuls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eskul_id'     => 'required|exists:eskul,id',
            'tanggal'      => 'required|date',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'radius_meter' => 'required|integer|min:10',
        ]);

        JadwalEskul::create([
            'eskul_id'     => $request->eskul_id,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'radius_meter' => $request->radius_meter,
            'status'       => 'aktif',
            'created_by'   => Auth::id(),
        ]);

        return redirect()
            ->route('admin.jadwal-eskul.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(JadwalEskul $jadwalEskul)
    {
        $eskuls = Eskul::orderBy('nama_eskul')->get();
        return view('admin.jadwal_eskul.edit', compact('jadwalEskul', 'eskuls'));
    }

    public function update(Request $request, JadwalEskul $jadwalEskul)
    {
        $request->validate([
            'eskul_id'     => 'required|exists:eskul,id',
            'tanggal'      => 'required|date',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'radius_meter' => 'required|integer|min:10',
            'status'       => 'required|in:aktif,dibatalkan',
        ]);

        $jadwalEskul->update([
            'eskul_id'     => $request->eskul_id,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'radius_meter' => $request->radius_meter,
            'status'       => $request->status,
        ]);

        return redirect()
            ->route('admin.jadwal-eskul.index')
            ->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy(JadwalEskul $jadwalEskul)
    {
        if ($jadwalEskul->sesiAbsensi()->exists()) {
            return back()->with('error', 'Tidak bisa menghapus jadwal yang sudah memiliki sesi absensi');
        }

        $jadwalEskul->delete();

        return back()->with('success', 'Jadwal berhasil dihapus');
    }
}
