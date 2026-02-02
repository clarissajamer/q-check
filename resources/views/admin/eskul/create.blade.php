@extends('layouts.app')

@section('title', 'Tambah Eskul')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Tambah Eskul Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Isi formulir berikut untuk menambahkan ekstrakurikuler.</p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.eskul.store') }}">
                @csrf
                @include('admin.eskul._form')
            </form>
        </div>
    </div>
</div>
@endsection
