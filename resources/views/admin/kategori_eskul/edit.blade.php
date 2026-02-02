@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Edit Kategori</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui nama kategori.</p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.kategori-eskul.update', $kategoriEskul) }}">
                @csrf
                @method('PUT')
                @include('admin.kategori_eskul._form')
            </form>
        </div>
    </div>
</div>
@endsection
