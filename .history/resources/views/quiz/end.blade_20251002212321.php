<!-- resources/views/end.blade.php -->

@extends('layouts.app') <!-- kalau pakai layout utama -->
@section('content')
<div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md w-full text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            Quiz Selesai 🎉
        </h1>

        <p class="text-lg text-gray-600 mb-2">
            Judul: <span class="font-semibold">{{ $title }}</span>
        </p>

        <p class="text-xl font-bold text-blue-600 mb-6">
            Skor Kamu: {{ $score }}
        </p>

        <a href="{{ route('library.index') }}" 
           class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            Kembali ke Library
        </a>
    </div>
</div>
@endsection
