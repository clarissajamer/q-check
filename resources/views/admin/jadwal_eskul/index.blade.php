@extends('layouts.app')

@section('title', 'Jadwal Eskul')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Ekstrakurikuler</h1>
            <p class="text-sm text-gray-500">Kelola jadwal kegiatan eskul.</p>
        </div>
        <a href="{{ route('admin.jadwal-eskul.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:outline-none focus:border-primary-900 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
            + Tambah Jadwal
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eskul</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi & Radius</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($jadwals as $jadwal)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $jadwal->eskul->nama }}</div>
                            <div class="text-xs text-gray-500">{{ ucfirst($jadwal->hari) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             <div class="text-sm text-gray-900">{{ $jadwal->radius_meter }} Meter</div>
                             <div class="text-xs text-gray-500">Lat: {{ $jadwal->latitude ?? '-' }}, Long: {{ $jadwal->longitude ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             @if($jadwal->status === 'aktif')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <!-- Tombol Buka Absensi -->
                            @if($jadwal->status === 'aktif')
                                @if($jadwal->sesiAbsensi()->where('status', 'aktif')->exists())
                                    <a href="{{ route('admin.sesi-absensi.qr', $jadwal->sesiAbsensi()->where('status', 'aktif')->first()->id) }}" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-md hover:bg-green-200 transition-colors">
                                        Lihat QR
                                    </a>
                                @else
                                    <form action="{{ route('admin.jadwal-eskul.buka-absensi', $jadwal->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors">
                                            Buka Absensi
                                        </button>
                                    </form>
                                @endif
                            @endif

                            <a href="{{ route('admin.jadwal-eskul.edit', $jadwal->id) }}" class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 px-3 py-1 rounded-md transition-colors">Edit</a>
                            
                            <form action="{{ route('admin.jadwal-eskul.destroy', $jadwal->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-colors">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p>Belum ada jadwal eskul.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
