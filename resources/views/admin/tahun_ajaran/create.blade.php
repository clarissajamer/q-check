@extends('layouts.app')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Tambah Tahun Ajaran</h2>
            <p class="text-sm text-gray-500 mt-1">Buat tahun ajaran baru.</p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.tahun-ajaran.store') }}">
                @csrf
                @include('admin.tahun_ajaran._form')
            </form>
        </div>
    </div>
</div>
@endsection
