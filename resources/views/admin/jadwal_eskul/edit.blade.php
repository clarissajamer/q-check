@extends('layouts.app')

@section('title', 'Edit Jadwal Eskul')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Edit Jadwal Eskul</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui data jadwal kegiatan ekstrakurikuler.</p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.jadwal-eskul.update', $jadwalEskul->id) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Eskul</label>
                        <select name="eskul_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="">-- Pilih Eskul --</option>
                            @foreach ($eskuls as $eskul)
                                <option value="{{ $eskul->id }}" @selected($jadwalEskul->eskul_id == $eskul->id)>{{ $eskul->nama_eskul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="aktif" @selected($jadwalEskul->status === 'aktif')>Aktif</option>
                            <option value="dibatalkan" @selected($jadwalEskul->status === 'dibatalkan')>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $jadwalEskul->tanggal }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" value="{{ $jadwalEskul->jam_mulai }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" value="{{ $jadwalEskul->jam_selesai }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Latitude (Opsional)</label>
                         <input type="text" name="latitude" value="{{ $jadwalEskul->latitude }}" placeholder="-6.12345" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                    <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Longitude (Opsional)</label>
                         <input type="text" name="longitude" value="{{ $jadwalEskul->longitude }}" placeholder="106.12345" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Radius (Meter)</label>
                        <input type="number" name="radius_meter" value="{{ $jadwalEskul->radius_meter }}" required min="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('admin.jadwal-eskul.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm font-medium">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
