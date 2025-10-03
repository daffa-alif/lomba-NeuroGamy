@extends('layouts.app')

@section('title', 'Quiz Time')

@section('content')
<div class="flex items-center justify-center py-12 px-6">
    <div class="bg-white p-10 rounded-xl shadow-2xl w-full max-w-3xl animate-fade-in-up">
        
        <!-- Quiz Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold mb-2">Quiz for: {{ $book->book_title }}</h1>
            <p class="text-gray-600">Answer the following questions to test your knowledge.</p>
        </div>

        <form id="quiz-form" method="POST" action="{{ route('quiz.submit') }}" class="space-y-10">
            @csrf
            <input type="hidden" name="books_id" value="{{ $book->id }}">
            <input type="hidden" name="quiz_data" value="{{ json_encode($quiz) }}">
            <input type="hidden" name="scorelog_id" value="{{ $scorelog_id }}">

            @foreach($quiz['questions'] as $index => $question)
                <div class="border-t border-gray-200 pt-8">
                    <p class="text-xl font-semibold text-gray-900 mb-6">
                        <span class="text-gray-500">{{ $index + 1 }}.</span> {{ $question['question'] }}
                    </p>
                    <div class="space-y-4">
                        @foreach($question['options'] as $key => $option)
                            <label class="block p-4 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition cursor-pointer has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                                <div class="flex items-center">
                                    <input type="radio"
                                           name="answers[{{ $question['id'] }}]"
                                           value="{{ $key }}"
                                           class="h-5 w-5 text-black focus:ring-black border-gray-400">
                                    <span class="ml-4 text-gray-800 font-medium">{{ $option }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="pt-6">
                <button type="submit"
                        class="w-full bg-black text-white px-6 py-3 rounded-lg font-semibold text-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition duration-150">
                    Submit Jawaban
                </button>
            </div>
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
