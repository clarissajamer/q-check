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
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|uuid',
            'tahun_ajaran_id' => 'required|uuid',
        ]);

        Eskul::create($request->all());

        return redirect()
            ->route('eskul.index')
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
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|uuid',
            'tahun_ajaran_id' => 'required|uuid',
        ]);

        $eskul->update($request->all());

        return redirect()
            ->route('eskul.index')
            ->with('success', 'Eskul berhasil diupdate');
    }

    public function destroy(Eskul $eskul)
    {
        $eskul->delete();

        return back()->with('success', 'Eskul dihapus');
    }
//     public function dashboard()
// {
//     return view('admin.dashboard', [
//         'totalEskul' => Eskul::count(),
//         'jadwalHariIni' => JadwalEskul::whereDate('tanggal', now())->count(),
//         'absensiAktif' => SesiAbsensi::where('status','dibuka')->exists(),
//     ]);
// }

}
