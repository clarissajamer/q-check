@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
        <span class="text-sm font-medium text-gray-500">{{ now()->format('l, d F Y') }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Total Eskul -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:scale-105 duration-200">
            <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Ekstrakurikuler</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalEskul ?? 0 }}</p>
            </div>
        </div>

        <!-- Card 2: Jadwal Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:scale-105 duration-200">
            <div class="p-3 rounded-full bg-purple-50 text-purple-600 mr-4">
               <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Jadwal Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ $jadwalHariIni ?? 0 }}</p>
            </div>
        </div>

        <!-- Card 3: Status Absensi -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:scale-105 duration-200">
            <div class="p-3 rounded-full {{ ($absensiAktif ?? false) ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }} mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status Absensi</p>
                <p class="text-md font-bold {{ ($absensiAktif ?? false) ? 'text-green-600' : 'text-gray-500' }}">
                    {{ ($absensiAktif ?? false) ? 'SEDANG AKTIF' : 'TIDAK ADA' }}
                </p>
            </div>
        </div>
    </div>
    
    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center mt-8">
        <h3 class="text-2xl font-bold text-primary-700 mb-2">Selamat Datang, Admin!</h3>
        <p class="text-gray-600">Anda dapat mengelola ekstrakurikuler, jadwal, dan absensi dari sini.</p>
    </div>
</div>
@endsection
