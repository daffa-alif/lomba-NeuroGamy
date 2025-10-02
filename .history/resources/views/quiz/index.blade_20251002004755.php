@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-lg mt-8">
    <h2 class="text-xl font-bold text-blue-700 mb-4">
        Quiz untuk: {{ $book->book_title }}
    </h2>

    <form action="{{ route('quiz.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="book_id" value="{{ $book->id }}">

        @foreach ($quiz['questions'] as $index => $q)
            <div class="mb-6">
                <p class="font-semibold text-gray-800 mb-2">
                    {{ $index + 1 }}. {{ $q['question'] }}
                </p>

                @foreach ($q['options'] as $optIndex => $option)
                    <label class="block mb-1">
                        <input type="radio" 
                               name="answers[{{ $index }}]" 
                               value="{{ $option }}">
                        {{ $option }}
                    </label>
                @endforeach
            </div>
        @endforeach

        <button type="submit" 
                class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Selesai & Simpan Jawaban
        </button>
    </form>
</div>
@endsection
