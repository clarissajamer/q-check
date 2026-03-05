@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Siswa</h2>
        <a href="{{ route('admin.users.index', 'siswa') }}" class="text-gray-500 hover:text-gray-700">Kembali</a>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">NIS</label>
            <input type="text" name="nis" value="{{ old('nis', $user->siswa->nis ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nis') border-red-500 @enderror" placeholder="Nomor Induk Siswa">
            @error('nis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-lg mb-4">
            <p class="text-sm text-yellow-700">Kosongkan jika tidak ingin mengubah password.</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('password') border-red-500 @enderror" placeholder="Opsional">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Opsional">
        </div>

        @if(isset($tahunAjarans) && isset($eskuls))
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tahun Ajaran</label>
            <select name="tahun_ajaran_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tahun_ajaran_id') border-red-500 @enderror">
                <option value="">Pilih Tahun Ajaran</option>
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $user->siswa?->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
                        {{ $ta->nama }}
                    </option>
                @endforeach
            </select>
            @error('tahun_ajaran_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Ekstrakurikuler (Minimal 1)</label>
            <select name="eskuls[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('eskuls') border-red-500 @enderror" style="height: 120px;">
                @foreach($eskuls as $eskul)
                    <option value="{{ $eskul->id }}" {{ in_array($eskul->id, old('eskuls', $user->siswa ? $user->siswa->eskul->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                        {{ $eskul->nama_eskul }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Tahan tombol Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu.</p>
            @error('eskuls') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        @endif

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Perbarui Siswa</button>
        </div>
    </form>
</div>
@endsection
