@extends('layouts.app')

@section('title', 'QR Code Absensi')

@section('content')
<div class="max-w-4xl mx-auto text-center space-y-8">
    
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Scan QR Code Absensi</h1>
        <p class="text-gray-500 mb-6">Silakan minta siswa untuk scan QR Code di bawah ini.</p>
        
        <div class="flex justify-center mb-6">
            <div class="bg-white p-4 rounded-xl shadow-inner border border-gray-200">
                <div class="flex flex-col items-center justify-center">
                    @if($token)
                         <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ $token->token }}" 
                              alt="QR Code Absensi" 
                              class="w-64 h-64 border-4 border-white rounded-lg shadow-md mb-4">
                         
                         <div class="bg-gray-100 px-4 py-2 rounded-lg text-center max-w-md w-full">
                            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Token</p>
                            <p class="text-sm text-gray-700 font-mono break-all">{{ $token->token }}</p>
                            <p class="text-xs text-red-500 mt-2 font-semibold">Valid until: {{ \Carbon\Carbon::parse($token->expired_at)->format('H:i:s') }}</p>
                        </div>
                    @else
                        <div class="bg-red-50 text-red-600 p-4 rounded-lg">
                            Token tidak ditemukan atau error.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-center space-x-4">
             <form action="{{ route('guru.sesi-absensi.tutup', $sesi->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-bold shadow-lg hover:bg-red-700 transition-transform transform hover:scale-105" onclick="return confirm('Yakin ingin menutup sesi absensi? QR Code tidak akan berlaku lagi.')">
                    Tutup Sesi Absensi
                </button>
            </form>
            <a href="{{ route('guru.jadwal.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition-colors">
                Kembali
            </a>
        </div>
    </div>

    <!-- Auto Refresh Script for Token (Optional but good for real time) -->
    <script>
        // Reload page automatically when token expires (approx 2 mins)
        // Or simpler: Meta refresh
        setTimeout(function(){
           window.location.reload(1);
        }, 121000); // 2 minutes+buffer
    </script>
</div>
@endsection
