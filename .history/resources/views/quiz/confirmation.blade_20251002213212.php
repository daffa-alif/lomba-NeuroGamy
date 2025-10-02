@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow p-6 rounded">
        <h1 class="text-2xl font-bold">Konfirmasi Quiz</h1>
        <p>Buku: <strong>{{ $book->book_title }}</strong></p>
        <p>Halaman yang dibaca: {{ $pages ?? 'Tidak ada' }}</p>

        <form action="{{ route('quiz.submit') }}" method="POST">
    @csrf
    <input type="hidden" name="books_id" value="{{ $book->id }}">
    <input type="hidden" name="scorelog_id" value="{{ $scorelog_id }}">
    <!-- form quiz lainnya -->
    <button type="submit">Submit</button>
</form>

    </div>
</div>
@endsection
