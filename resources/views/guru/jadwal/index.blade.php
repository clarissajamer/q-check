@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Ekstrakurikuler</h1>
            <p class="text-sm text-gray-500">Pilih jadwal untuk memulai absensi.</p>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eskul</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($jadwals as $jadwal)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $jadwal->eskul->nama_eskul }}</div>
                            <div class="text-xs text-gray-500">{{ ucfirst($jadwal->hari) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             <div class="text-sm text-gray-900">{{ $jadwal->radius_meter }} Meter</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                             @if($jadwal->sesiAbsensi()->where('status', 'aktif')->exists())
                                <a href="{{ route('guru.sesi-absensi.qr', $jadwal->sesiAbsensi()->where('status', 'aktif')->first()->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-9.975c-.322.213-.69.324-1.075.324-.385 0-.753-.11-1.075-.324-.323-.215-.6-.523-.79-.876-.188-.352-.292-.746-.292-1.15s.104-.798.292-1.15c.19-.353.467-.66.79-.876.322-.213.69-.324 1.075-.324.385 0 .753.11 1.075.324.323.215.6.523.79.876.188.352.292.746.292 1.15s-.104.798-.292 1.15c-.19.353-.467.66-.79.876zM7 7a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2V7zm10 0a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2h-6a2 2 0 01-2-2V7zM7 17a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z"></path></svg>
                                    Lihat QR
                                </a>
                            @else
                                <form action="{{ route('guru.jadwal-eskul.buka-absensi', $jadwal->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                        Buka Absensi
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                            Belum ada jadwal aktif.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
