@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    @if (!isset($quiz) || empty($quiz['questions']))
        <div class="bg-red-100 border border-red-300 p-4 rounded text-red-800">
            Quiz tidak tersedia. Silakan kembali ke <a href="{{ route('library.index') }}" class="underline">Library</a>.
        </div>
    @else
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6 md:p-8">
            <h1 class="text-3xl font-bold">{{ $quiz['title'] ?? 'Quiz' }}</h1>
            <p>{{ $quiz['description'] ?? '' }}</p>

            <form id="quiz-form" method="POST" action="{{ route('quiz.submit') }}">
                @csrf
                <input type="hidden" name="books_id" value="{{ $book->id }}">
                <input type="hidden" name="quiz_data" value="{{ json_encode($quiz) }}">

                @foreach($quiz['questions'] as $index => $question)
                    <div class="mt-6">
                        <p class="font-semibold">{{ $index + 1 }}. {{ $question['question'] }}</p>
                        @foreach($question['options'] as $key => $option)
                            <label class="block mt-1">
                                <input type="radio"
                                       name="answers[{{ $question['id'] }}]"
                                       value="{{ $key }}">
                                {{ $key }}. {{ $option }}
                            </label>
                        @endforeach
                    </div>
                @endforeach

                <button type="submit"
                        class="mt-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Submit Jawaban
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
