<form method="POST" action="{{ route('admin.jadwal-eskul.store') }}">
    @csrf

    <div>
        <label>Eskul</label>
        <select name="eskul_id" required>
            <option value="">-- Pilih Eskul --</option>
            @foreach ($eskuls as $eskul)
                <option value="{{ $eskul->id }}">{{ $eskul->nama }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Tanggal</label>
        <input type="date" name="tanggal" required>
    </div>

    <div>
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" required>
    </div>

    <div>
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" required>
    </div>

    <div>
        <label>Latitude (opsional)</label>
        <input type="text" name="latitude">
    </div>

    <div>
        <label>Longitude (opsional)</label>
        <input type="text" name="longitude">
    </div>

    <div>
        <label>Radius (meter)</label>
        <input type="number" name="radius_meter" value="50" required>
    </div>

    <button type="submit">Simpan Jadwal</button>
</form>
