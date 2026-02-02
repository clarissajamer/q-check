<div class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Eskul</label>
        <input type="text" name="nama_eskul" value="{{ old('nama_eskul', $eskul->nama_eskul ?? '') }}" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors"
            placeholder="Contoh: Futsal, Basket, Pramuka">
        @error('nama_eskul')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
        <select name="kategori_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}"
                    @selected(old('kategori_id', $eskul->kategori_id ?? '') == $k->id)>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
         @error('kategori_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
        <select name="tahun_ajaran_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
            <option value="">-- Pilih Tahun Ajaran --</option>
            @foreach($tahunAjaran as $t)
                <option value="{{ $t->id }}"
                    @selected(old('tahun_ajaran_id', $eskul->tahun_ajaran_id ?? '') == $t->id)>
                    {{ $t->nama }}
                </option>
            @endforeach
        </select>
        @error('tahun_ajaran_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-4 flex justify-end space-x-3">
        <a href="{{ route('admin.eskul.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
            Batal
        </a>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm font-medium">
            Simpan
        </button>
    </div>
</div>
