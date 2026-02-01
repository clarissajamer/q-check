@extends('layouts.admin')

@section('content')
<h2>QR Absensi</h2>

<p><b>Eskul:</b> {{ $sesi->jadwal->eskul->nama }}</p>
<p><b>Expired:</b> {{ $token->expired_at }}</p>

<img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ $token->token }}">

<form method="POST" action="{{ route('admin.sesi-absensi.tutup', $sesi->id) }}">
    @csrf
    <button>Tutup Sesi</button>
</form>
@endsection
