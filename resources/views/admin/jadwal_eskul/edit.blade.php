<select name="status">
    <option value="aktif" @selected($jadwalEskul->status === 'aktif')>Aktif</option>
    <option value="dibatalkan" @selected($jadwalEskul->status === 'dibatalkan')>Dibatalkan</option>
</select>
