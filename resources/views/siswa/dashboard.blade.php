<form method="POST" action="{{ route('siswa.absen') }}">
    @csrf

    <input type="hidden" name="latitude" id="lat">
    <input type="hidden" name="longitude" id="lng">

    <button type="submit">ABSEN SEKARANG</button>
</form>

<script>
navigator.geolocation.getCurrentPosition(function(pos) {
    document.getElementById('lat').value = pos.coords.latitude;
    document.getElementById('lng').value = pos.coords.longitude;
});
</script>
