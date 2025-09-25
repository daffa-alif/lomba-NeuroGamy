@extends('Admin.AdminLayouts.app')

@section('title', 'Books Management')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
   <button id="btnAddBook" type="button" class="bg-blue-600 text-white px-3 py-1 rounded">
    Add Book
</button>


    {{-- Table --}}
    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Title</th>
                <th class="p-2 border">Classification</th>
                <th class="p-2 border">File</th>
                <th class="p-2 border">Description</th>
                <th class="p-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
            <tr>
                <td class="p-2 border">{{ $book->book_title }}</td>
                <td class="p-2 border">{{ $book->classification->classification ?? '-' }}</td>
                <td class="p-2 border">{{ $book->file_name }}</td>
                <td class="p-2 border">{{ $book->book_description }}</td>
                <td class="p-2 border">
                    <button class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                    <button class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Modal Book Form --}}
<div id="bookModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-3">Add Book</h3>
        <form id="bookForm">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="book_title" class="w-full border rounded p-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium">Classification</label>
                <div class="flex gap-2">
                    <select name="classification_id" id="classificationSelect" class="w-full border rounded p-2">
                        <option value="">-- Select Classification --</option>
                        @foreach($classifications as $c)
                            <option value="{{ $c->id }}">{{ $c->classification }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="btnAddClassification" class="bg-blue-500 text-white px-3 rounded">+</button>
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium">File Name</label>
                <input type="text" name="file_name" class="w-full border rounded p-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="book_description" class="w-full border rounded p-2"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="closeBookModal" class="px-3 py-1 border rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // open book modal
    $('#btnAddBook').on('click', function() {
        $('#bookModal').removeClass('hidden');
    });

    // close modal
    $('#closeBookModal').on('click', function() {
        $('#bookModal').addClass('hidden');
    });

    // submit book
    $('#bookForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('books.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                location.reload();
            }
        });
    });
});
</script>
@endpush
