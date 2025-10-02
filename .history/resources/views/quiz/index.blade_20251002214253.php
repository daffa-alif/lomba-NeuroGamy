@extends("layouts.app")
<form id="quiz-form" method="POST" action="{{ route('quiz.submit') }}" class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg">
    @csrf
    <input type="hidden" name="books_id" value="{{ $book->id }}">
    <input type="hidden" name="quiz_data" value="{{ json_encode($quiz) }}">
    <input type="hidden" name="scorelog_id" value="{{ $scorelog_id }}">

    @foreach($quiz['questions'] as $index => $question)
        <div class="mb-8">
            <p class="text-lg font-semibold text-gray-800 mb-4">{{ $index + 1 }}. {{ $question['question'] }}</p>
            @foreach($question['options'] as $key => $option)
                <label class="block mb-3 flex items-center">
                    <input type="radio"
                           name="answers[{{ $question['id'] }}]"
                           value="{{ $key }}"
                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="ml-3 text-gray-700">{{ $key }}. {{ $option }}</span>
                </label>
            @endforeach
        </div>
    @endforeach

    <button type="submit"
            class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
        Submit Jawaban
    </button>
</form>
