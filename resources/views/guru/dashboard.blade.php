@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Guru</h1>
        <span class="text-sm font-medium text-gray-500">{{ now()->format('l, d F Y') }}</span>
    </div>

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1/3 bg-white opacity-5 transform skew-x-12 translate-x-10"></div>
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, Bapak/Ibu Guru!</h2>
            <p class="text-emerald-100">Pantau kegiatan ekstrakurikuler siswa dengan mudah.</p>
        </div>
    </div>

    <!-- Stats/Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card Example -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <h3 class="font-semibold text-gray-700 mb-2">Informasi</h3>
            <p class="text-gray-500 text-sm">Fitur pemantauan absensi dan jadwal akan tampil di sini.</p>
        </div>
    </div>
</div>
@endsection
