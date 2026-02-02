<div class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
        <div class="mt-1 flex rounded-md shadow-sm">
             <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                ID (Auto)
            </span>
            <input type="text" disabled value="{{ $kategoriEskul->id ?? 'Auto-generated' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 bg-gray-100 text-gray-500 sm:text-sm">
        </div>
        <p class="mt-1 text-xs text-gray-500">ID dibuat otomatis oleh sistem.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
        <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategoriEskul->nama_kategori ?? '') }}" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors"
            placeholder="Contoh: Olahraga, Seni, Akademik">
        @error('nama_kategori')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
        <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">{{ old('deskripsi', $kategoriEskul->deskripsi ?? '') }}</textarea>
    </div>

    <div class="pt-4 flex justify-end space-x-3">
        <a href="{{ route('admin.kategori-eskul.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
            Batal
        </a>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm font-medium">
            Simpan
        </button>
    </div>
</div>
