@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Tambah Kategori Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Buat kategori baru untuk mengelompokkan eskul.</p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.kategori-eskul.store') }}">
                @csrf
                @include('admin.kategori_eskul._form')
            </form>
        </div>
    </div>
</div>
@endsection
