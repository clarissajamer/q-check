<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriEskul;
use Illuminate\Http\Request;

class KategoriEskulController extends Controller
{
    public function index()
    {
        $kategori = KategoriEskul::latest()->get();
        return view('admin.kategori_eskul.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori_eskul.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_eskul,nama_kategori',
        ]);

        KategoriEskul::create($request->all());

        return redirect()
            ->route('admin.kategori-eskul.index')
            ->with('success', 'Kategori eskul berhasil ditambahkan');
    }

    public function edit(KategoriEskul $kategoriEskul)
    {
        return view('admin.kategori_eskul.edit', compact('kategoriEskul'));
    }

    public function update(Request $request, KategoriEskul $kategoriEskul)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_eskul,nama_kategori,' . $kategoriEskul->id,
        ]);

        $kategoriEskul->update($request->all());

        return redirect()
            ->route('admin.kategori-eskul.index')
            ->with('success', 'Kategori eskul berhasil diperbarui');
    }

    public function destroy(KategoriEskul $kategoriEskul)
    {
        if ($kategoriEskul->eskul()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang digunakan oleh eskul');
        }

        $kategoriEskul->delete();

        return back()->with('success', 'Kategori eskul berhasil dihapus');
    }
}
