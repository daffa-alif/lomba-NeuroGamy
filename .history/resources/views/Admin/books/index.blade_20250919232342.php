@extends('Admin.AdminLayouts.app')

@section('content')
<div class="ml-64 p-6">
    <h1 class="text-2xl font-bold mb-4">Books Management</h1>

    <!-- Form -->
    <form id="bookForm" class="bg-white p-4 rounded shadow mb-6">
        @csrf
        <input type="hidden" name="id" id="book_id">

        <div class="mb-4">
            <label class="block">Title</label>
            <input type="text" name="book_title" id="book_title" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block">Classification</label>
            <select name="classification_id" id="classification_id" class="w-full border p-2 rounded">
                <option value="">-- Select Classification --</option>
                @foreach($classifications as $c)
                    <option value="{{ $c->id }}">{{ $c->classification }}</option>
                @endforeach
            </select>
            <input type="text" name="classification_name" id="classification_name" placeholder="Or type new classification"
                class="w-full border p-2 rounded mt-2">
        </div>

        <div class="mb-4">
            <label class="block">File Name</label>
            <input type="text" name="file_name" id="file_name" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block">Description</label>
            <textarea name="book_description" id="book_description" class="w-full border p-2 rounded"></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    </form>

    <!-- Table -->
    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-2">Title</th>
                <th class="p-2">Classification</th>
                <th class="p-2">File</th>
                <th class="p-2">Description</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody id="booksTable">
            @foreach($books as $book)
                <tr data-id="{{ $book->id }}">
                    <td class="p-2">{{ $book->book_title }}</td>
                    <td class="p-2">{{ $book->classification->classification ?? '-' }}</td>
                    <td class="p-2">{{ $book->file_name }}</td>
                    <td class="p-2">{{ $book->book_description }}</td>
                    <td class="p-2">
                        <button class="editBtn bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                        <button class="deleteBtn bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
$(document).ready(function () {
    // Create / Update
    $('#bookForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#book_id').val();
        let url = id ? `/admin/books/${id}` : `/admin/books`;
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(res) {
                location.reload(); // bisa diganti update row tanpa reload
            }
        });
    });

    // Edit
    $(document).on('click', '.editBtn', function() {
        let row = $(this).closest('tr');
        $('#book_id').val(row.data('id'));
        $('#book_title').val(row.find('td:eq(0)').text());
        $('#classification_name').val('');
        $('#file_name').val(row.find('td:eq(2)').text());
        $('#book_description').val(row.find('td:eq(3)').text());
    });

    // Delete
    $(document).on('click', '.deleteBtn', function() {
        if(!confirm('Are you sure?')) return;
        let row = $(this).closest('tr');
        let id = row.data('id');

        $.ajax({
            url: `/admin/books/${id}`,
            type: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(res) {
                row.remove();
            }
        });
    });
});
</script>
@endsection
