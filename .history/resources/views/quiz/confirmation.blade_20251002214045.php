@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow p-6 rounded">
        <h1 class="text-2xl font-bold">Konfirmasi Quiz</h1>
        <p>Buku: <strong>{{ $book->book_title }}</strong></p>
        <p>Halaman yang dibaca: {{ $pages ?? 'Tidak ada' }}</p>

        <a href="{{ route('quiz.index', ['book_id' => $book->id, 'scorelog_id' => $scorelog_id]) }}"
   class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
    Mulai Quiz
</a>

    </div>
</div>
@endsection
