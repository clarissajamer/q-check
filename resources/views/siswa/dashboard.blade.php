@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Siswa</h1>
        <span class="text-sm font-medium text-gray-500">{{ now()->format('l, d F Y') }}</span>
    </div>

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-2xl shadow-lg p-8 text-white">
        <h2 class="text-3xl font-bold mb-2">Halo, {{ auth()->user()->name ?? 'Siswa' }}!</h2>
        <p class="text-primary-100">Selamat datang kembali di Q-CHECK. Jangan lupa isi absensi ya!</p>
    </div>

    <!-- Absensi Status Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Status Absensi Eskul</h3>
            @if($sesiAktif)
                <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full animate-pulse">SESI AKTIF</span>
            @else
                <span class="px-3 py-1 text-xs font-bold text-gray-500 bg-gray-200 rounded-full">TIDAK AKTIF</span>
            @endif
        </div>
        
        <div class="p-8 text-center">
            @if($sesiAktif)
                <div class="mb-6">
                    <div class="inline-block p-4 rounded-full bg-green-50 text-green-600 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $sesiAktif->jadwalEskul->eskul->nama ?? 'Eskul' }}</h4>
                    <p class="text-gray-500">Silakan lakukan absensi untuk kegiatan ini.</p>
                </div>

                <form action="{{ route('siswa.absen') }}" method="POST">
                    @csrf
                    <input type="hidden" name="sesi_absensi_id" value="{{ $sesiAktif->id }}">
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Klik Disini untuk Absen
                    </button>
                </form>
            @else
                <div class="py-8">
                    <div class="inline-block p-4 rounded-full bg-gray-50 text-gray-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </div>
                    <p class="text-gray-500 text-lg">Belum ada sesi absensi yang dibuka saat ini.</p>
                    <p class="text-gray-400 text-sm mt-2">Cek kembali nanti jika kegiatan eskul dimulai.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
