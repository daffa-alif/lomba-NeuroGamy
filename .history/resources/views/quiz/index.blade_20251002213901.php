<form id="quiz-form" method="POST" action="{{ route('quiz.submit') }}">
    @csrf
    <input type="hidden" name="books_id" value="{{ $book->id }}">
    <input type="hidden" name="quiz_data" value="{{ json_encode($quiz) }}">
    <input type="hidden" name="scorelog_id" value="{{ $scorelog_id }}"><!-- ✅ tambahkan ini -->

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
