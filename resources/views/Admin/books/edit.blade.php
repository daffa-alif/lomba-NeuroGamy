@extends('Admin.AdminLayouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Edit Book</h2>

    <form action="{{ route('books.update', $book) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <select name="classification_id" class="w-full border rounded-lg px-3 py-2">
            @foreach($classifications as $c)
                <option value="{{ $c->id }}" @selected($book->classification_id == $c->id)>
                    {{ $c->classification }}
                </option>
            @endforeach
        </select>

        <input type="text" name="book_title" value="{{ old('book_title', $book->book_title) }}"
               class="w-full border rounded-lg px-3 py-2">

      <input type="file" name="file_name" accept="application/pdf" 
       class="w-full border rounded-lg px-3 py-2">
@if($book->file_name)
    <p class="mt-2">Current File: 
        <a href="{{ asset('storage/books/'.$book->file_name) }}" target="_blank" class="text-blue-600 underline">
            View PDF
        </a>
    </p>
@endif

        <textarea name="book_description" rows="3"
               class="w-full border rounded-lg px-3 py-2">{{ old('book_description', $book->book_description) }}</textarea>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Update
        </button>
        <a href="{{ route('books.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
