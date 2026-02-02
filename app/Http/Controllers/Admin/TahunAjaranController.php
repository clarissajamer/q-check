<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::latest()->get();
        return view('admin.tahun_ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('admin.tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Jika status yang baru adalah aktif, nonaktifkan yang lain
        if ($request->status === 'aktif') {
            TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
        }

        TahunAjaran::create($request->all());

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('admin.tahun_ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Jika status diubah menjadi aktif, nonaktifkan yang lain
        if ($request->status === 'aktif' && $tahunAjaran->status !== 'aktif') {
             TahunAjaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
        }

        $tahunAjaran->update($request->all());

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        // Cek relasi jika perlu (misal jika ada siswa/eskul yang terikat)
        // if ($tahunAjaran->siswa()->exists()) { ... }

        $tahunAjaran->delete();

        return back()->with('success', 'Tahun ajaran berhasil dihapus');
    }
}
