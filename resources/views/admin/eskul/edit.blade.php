@extends('layouts.app')

@section('title', 'Edit Eskul')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Edit Eskul</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui data ekstrakurikuler.</p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.eskul.update', $eskul) }}">
                @csrf
                @method('PUT')
                @include('admin.eskul._form')
            </form>
        </div>
    </div>
</div>
@endsection
