@extends('layouts.app')

@section('title', 'Quiz Results')

@section('content')
<div class="flex items-center justify-center py-16 px-6">
    <div class="bg-white p-12 rounded-xl shadow-2xl w-full max-w-lg text-center animate-fade-in-up">
        
        <!-- Icon -->
        <div class="w-20 h-20 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="text-3xl font-bold mb-4">Quiz Selesai! 🎉</h1>
        
        <p class="text-gray-600 mb-2">
            Buku: <strong class="font-bold text-black">{{ $title }}</strong>
        </p>

        <p class="text-gray-600 mb-8">
            Skor Kamu:
        </p>
        
        <p class="text-6xl font-bold text-black mb-10">
            {{ $score }}
        </p>

        <a href="{{ route('library.index') }}" 
           class="w-full inline-block bg-black text-white py-3 px-8 rounded-md hover:bg-gray-800 transition font-semibold text-lg">
            Kembali ke Library
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
</style>
@endpush
