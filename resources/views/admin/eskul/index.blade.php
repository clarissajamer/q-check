<h1>Data Eskul</h1>

<a href="{{ route('eskul.create') }}">+ Tambah Eskul</a>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<table border="1" cellpadding="8">
    <tr>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Tahun Ajaran</th>
        <th>Aksi</th>
    </tr>

    @foreach($eskul as $e)
    <tr>
        <td>{{ $e->nama }}</td>
        <td>{{ $e->kategori->nama ?? '-' }}</td>
        <td>{{ $e->tahunAjaran->nama ?? '-' }}</td>
        <td>
            <a href="{{ route('eskul.edit', $e) }}">Edit</a>

            <form action="{{ route('eskul.destroy', $e) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Hapus?')">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
    