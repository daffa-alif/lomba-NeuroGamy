@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-xl p-6 max-w-md text-center">
        <h2 class="text-2xl font-bold text-blue-700 mb-4">Konfirmasi Sebelum Mulai Quiz</h2>
        <p class="text-gray-700 mb-2">📚 Buku: <strong>{{ $book->book_title }}</strong></p>
        <p class="text-gray-700 mb-4">📖 Halaman dibaca: <strong>{{ $pagesRead }}</strong></p>

        <div class="flex gap-4 mt-6 justify-center">
          <a href="{{ route('quiz.index', ['book_id' => $book->id]) }}"
   class="px-5 py-2 bg-green-600 text-white rounded-lg">
   ✅ Ya, Mulai Quiz
</a>

            <a href="{{ url()->previous() }}"
               class="px-5 py-2 rounded-lg bg-gray-400 text-white hover:bg-gray-500">
                ❌ Kembali
            </a>
        </div>
    </div>
</div>
@endsection
