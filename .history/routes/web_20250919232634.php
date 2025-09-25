@extends('AdminLayouts.app')

@section('title', 'Books Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Books Management</h1>
    <button id="btnAddBook" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
        <i class="fa fa-plus mr-2"></i> Add Book
    </button>
</div>

<!-- Table -->
<div class="bg-white p-6 rounded-lg shadow">
    <table class="min-w-full border" id="booksTable">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">Title</th>
                <th class="px-4 py-2 border">Classification</th>
                <th class="px-4 py-2 border">File</th>
                <th class="px-4 py-2 border">Description</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
                <tr data-id="{{ $book->id }}">
                    <td class="px-4 py-2 border">{{ $book->book_title }}</td>
                    <td class="px-4 py-2 border">{{ $book->classification->classification ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $book->file_name }}</td>
                    <td class="px-4 py-2 border">{{ $book->book_description }}</td>
                    <td class="px-4 py-2 border">
                        <button class="btnEdit bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Edit</button>
                        <button class="btnDelete bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div id="bookModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Book</h2>
        <form id="bookForm">
            @csrf
            <input type="hidden" id="book_id" name="id">

            <div class="mb-3">
                <label class="block mb-1 font-medium">Title</label>
                <input type="text" name="book_title" id="book_title" class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Classification</label>
                <select name="classification_id" id="classification_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">-- Select Classification --</option>
                    @foreach($classifications as $c)
                        <option value="{{ $c->id }}">{{ $c->classification }}</option>
                    @endforeach
                </select>
                <input type="text" id="new_classification" placeholder="Or type new classification"
                       class="w-full border rounded-lg px-3 py-2 mt-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">File Name</label>
                <input type="text" name="file_name" id="file_name" class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="book_description" id="book_description" rows="3"
                          class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" id="btnCloseModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    
</script>
@endsection
