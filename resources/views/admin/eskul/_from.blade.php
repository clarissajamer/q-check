<label>Nama Eskul</label>
<input type="text" name="nama" value="{{ old('nama', $eskul->nama ?? '') }}">

<br>

<label>Kategori</label>
<select name="kategori_id">
    @foreach($kategori as $k)
        <option value="{{ $k->id }}"
            @selected(old('kategori_id', $eskul->kategori_id ?? '') == $k->id)>
            {{ $k->nama }}
        </option>
    @endforeach
</select>

<br>

<label>Tahun Ajaran</label>
<select name="tahun_ajaran_id">
    @foreach($tahunAjaran as $t)
        <option value="{{ $t->id }}"
            @selected(old('tahun_ajaran_id', $eskul->tahun_ajaran_id ?? '') == $t->id)>
            {{ $t->nama }}
        </option>
    @endforeach
</select>

<br><br>

<button type="submit">Simpan</button>
