<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eskul;
use App\Models\KategoriEskul;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\JadwalEskul;
use App\Models\SesiAbsensi;

class EskulController extends Controller
{
    public function index()
    {
        $eskul = Eskul::with(['kategori', 'tahunAjaran'])->latest()->get();

        return view('admin.eskul.index', compact('eskul'));
    }

    public function create()
    {
        return view('admin.eskul.create', [
            'kategori' => KategoriEskul::all(),
            'tahunAjaran' => TahunAjaran::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_eskul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_eskul,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        Eskul::create([
            'nama_eskul' => $request->nama_eskul,
            'kategori_id' => $request->kategori_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'status' => 'aktif', // Default status
        ]);

        return redirect()
            ->route('admin.eskul.index')
            ->with('success', 'Eskul berhasil dibuat');
    }

    public function edit(Eskul $eskul)
    {
        return view('admin.eskul.edit', [
            'eskul' => $eskul,
            'kategori' => KategoriEskul::all(),
            'tahunAjaran' => TahunAjaran::all(),
        ]);
    }

    public function update(Request $request, Eskul $eskul)
    {
        $request->validate([
            'nama_eskul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_eskul,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        $eskul->update($request->all());

        return redirect()
            ->route('admin.eskul.index')
            ->with('success', 'Eskul berhasil diupdate');
    }

    public function destroy(Eskul $eskul)
    {
        $eskul->delete();

        return back()->with('success', 'Eskul dihapus');
    }
}
