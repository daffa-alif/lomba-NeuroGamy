@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-8 transform transition-all">
        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <div class="bg-blue-100 rounded-full p-6">
                <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-3">
            Selamat! 🎉
        </h1>
        
        <!-- Message -->
        <p class="text-center text-gray-600 mb-2">
            Anda telah menyelesaikan pembacaan buku
        </p>
        <p class="text-center text-xl font-semibold text-blue-600 mb-6">
            "{{ $book->book_title ?? 'Buku' }}"
        </p>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 rounded">
            <p class="text-sm text-gray-700">
                <span class="font-semibold">📖 Halaman dibaca:</span> {{ $pagesRead ?? 0 }} halaman
            </p>
        </div>

        <!-- Question -->
        <div class="text-center mb-8">
            <p class="text-lg font-medium text-gray-800">
                Apakah Anda ingin melanjutkan ke Quiz?
            </p>
            <p class="text-sm text-gray-500 mt-2">
                Quiz akan membantu menguji pemahaman Anda
            </p>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Yes Button -->
            <a href="{{ route('library.index') }}" 
               class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transform transition-all hover:scale-105 text-center">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Ya, Mulai Quiz
                </span>
            </a>

            <!-- No Button -->
            <a href="{{ route('library.index') }}" 
               class="flex-1 bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transform transition-all hover:scale-105 text-center">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Tidak, Kembali
                </span>
            </a>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                Anda dapat mengakses quiz kapan saja dari halaman library
            </p>
        </div>
    </div>
</div>

<!-- Optional: Add animation -->
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
    
    .bg-white {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endsection