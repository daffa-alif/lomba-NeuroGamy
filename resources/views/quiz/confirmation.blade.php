@extends('layouts.app')

@section('title', 'Quiz Confirmation')

@section('content')
<div class="flex items-center justify-center py-16 px-6">
    <div class="bg-white p-12 rounded-xl shadow-2xl w-full max-w-lg text-center animate-fade-in-up">
        
        <!-- Icon -->
        <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
        </div>

        <h1 class="text-3xl font-bold mb-4">You're Ready for a Quiz!</h1>
        
        <div class="text-gray-600 space-y-2 mb-8">
            <p>You have finished reading <strong class="font-bold text-black">{{ $pages ?? '0' }}</strong> pages of the book:</p>
            <p class="text-xl font-bold text-black">{{ $book->book_title }}</p>
        </div>

        <p class="text-gray-500 mb-6">Test your knowledge and see what you've learned. Are you ready to begin?</p>

        <form action="{{ route('quiz.index') }}" method="get">
            <input type="hidden" name="book_id" value="{{ $book->id }}">
            <input type="hidden" name="scorelog_id" value="{{ $scorelog_id }}">
            <button type="submit"
                    class="w-full bg-black text-white py-3 px-8 rounded-md hover:bg-gray-800 transition font-semibold text-lg">
                Mulai Quiz
            </button>
        </form>
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

