@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h2 class="text-xl font-semibold mb-4">Selamat Datang, Guru!</h2>
            <p class="mb-6">Silakan masuk ke menu Jadwal untuk memulai absensi ekstrakurikuler.</p>
            
            <a href="{{ route('guru.jadwal.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Lihat Jadwal & Mulai Absensi
            </a>
        </div>
    </div>
</div>
@endsection
