@extends('Admin.AdminLayouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Edit Book</h2>

   {{-- Create Form --}}
<form id="create-book-form" class="grid grid-cols-2 gap-4 mb-6" enctype="multipart/form-data">
    @csrf
    <select name="classification_id" id="classification_id" class="border rounded-lg px-3 py-2 col-span-2">
        <option value="">-- Select Classification --</option>
        @foreach($classifications as $c)
            <option value="{{ $c->id }}">{{ $c->classification }}</option>
        @endforeach
    </select>

    <input type="text" name="book_title" id="book_title" placeholder="Book title"
           class="border rounded-lg px-3 py-2 col-span-2">

    <input type="file" name="file_name" id="file_name" accept="application/pdf"
           class="border rounded-lg px-3 py-2 col-span-2">

    <textarea name="book_description" id="book_description" rows="2" 
              placeholder="Book description (optional)"
              class="border rounded-lg px-3 py-2 col-span-2"></textarea>

    <button type="submit" class="col-span-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Add Book
    </button>
</form>

</div>
@endsection
