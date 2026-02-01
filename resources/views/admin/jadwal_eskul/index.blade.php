@extends('layouts.admin')

@section('content')
<h1>Jadwal Eskul</h1>

<a href="{{ route('admin.jadwal-eskul.create') }}">
    + Tambah Jadwal
</a>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Eskul</th>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Jam</th>
            <th>Radius</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($jadwals as $jadwal)
            <tr>
                <td>{{ $jadwal->eskul->nama }}</td>

                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d-m-Y') }}</td>

                <td>{{ ucfirst($jadwal->hari) }}</td>

                <td>
                    {{ $jadwal->jam_mulai }}
                    -
                    {{ $jadwal->jam_selesai }}
                </td>

                <td>{{ $jadwal->radius_meter }} m</td>

                <td>
                    {{ $jadwal->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                </td>

                <td>
                    <a href="{{ route('admin.jadwal-eskul.edit', $jadwal->id) }}">
                        Edit
                    </a>

                    <form action="{{ route('admin.jadwal-eskul.destroy', $jadwal->id) }}"
                          method="POST"
                          style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin hapus jadwal ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" align="center">
                    Belum ada jadwal eskul
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
