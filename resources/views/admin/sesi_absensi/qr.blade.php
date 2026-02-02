@extends('layouts.app')

@section('title', 'QR Code Absensi')

@section('content')
<div class="max-w-4xl mx-auto text-center space-y-8">
    
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Scan QR Code Absensi</h1>
        <p class="text-gray-500 mb-6">Silakan minta siswa untuk scan QR Code di bawah ini melalui dashboard mereka.</p>
        
        <div class="flex justify-center mb-6">
            <div class="bg-white p-4 rounded-xl shadow-inner border border-gray-200">
                <!-- Placeholder for QR Code since we don't have the library setup here visibly, assuming backend handles it or use simple library -->
                <!-- Ideally use {!! QrCode::size(300)->generate($token->token) !!} but simpler: -->
                <div class="bg-gray-100 w-64 h-64 flex items-center justify-center rounded-lg">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-9.975c-.322.213-.69.324-1.075.324-.385 0-.753-.11-1.075-.324-.323-.215-.6-.523-.79-.876-.188-.352-.292-.746-.292-1.15s.104-.798.292-1.15c.19-.353.467-.66.79-.876.322-.213.69-.324 1.075-.324.385 0 .753.11 1.075.324.323.215.6.523.79.876.188.352.292.746.292 1.15s-.104.798-.292 1.15c-.19.353-.467.66-.79.876zM7 7a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2V7zm10 0a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2h-6a2 2 0 01-2-2V7zM7 17a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4z"></path></svg>
                        <p class="text-sm text-gray-500 font-mono break-all">{{ $token->token ?? 'TOKEN-ERROR' }}</p>
                        <p class="text-xs text-red-500 mt-2">QR Library Not Found (Placeholder)</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center space-x-4">
             <form action="{{ route('admin.sesi-absensi.tutup', $sesi->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-bold shadow-lg hover:bg-red-700 transition-transform transform hover:scale-105">
                    Tutup Sesi Absensi
                </button>
            </form>
            <a href="{{ route('admin.jadwal-eskul.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition-colors">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-blue-800">
        <p><strong>Info:</strong> Token ini berlaku selama 2 menit. Refresh halaman jika token kadaluarsa (Logical improvement needed for auto-refresh).</p>
    </div>
    
</div>
@endsection
