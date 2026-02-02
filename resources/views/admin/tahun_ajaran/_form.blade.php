<div class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
        <input type="text" name="nama" value="{{ old('nama', $tahunAjaran->nama ?? '') }}" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors"
            placeholder="Contoh: 2024/2025">
        @error('nama')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
            <option value="nonaktif" @selected(old('status', $tahunAjaran->status ?? '') == 'nonaktif')>Nonaktif</option>
            <option value="aktif" @selected(old('status', $tahunAjaran->status ?? '') == 'aktif')>Aktif</option>
        </select>
        <p class="text-xs text-gray-500 mt-1">Jika dipilih Aktif, tahun ajaran lain akan otomatis dinonaktifkan.</p>
        @error('status')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-4 flex justify-end space-x-3">
        <a href="{{ route('admin.tahun-ajaran.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
            Batal
        </a>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm font-medium">
            Simpan
        </button>
    </div>
</div>
