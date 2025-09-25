@extends('Admin.AdminLayouts.app')

@section('title', 'Books Management')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Books Management (AJAX CRUD)</h1>

    <!-- Add Button -->
    <button id="btnAdd" class="mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Add Book
    </button>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full table-auto border-collapse" id="booksTable">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">#</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Title</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Description</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Classification</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                <tr data-id="{{ $book->id }}">
                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2">{{ $book->book_title }}</td>
                    <td class="px-4 py-2">{{ $book->book_description }}</td>
                    <td class="px-4 py-2">{{ $book->classification->classification ?? '-' }}</td>
                    <td class="px-4 py-2">
                        <button class="btnEdit text-blue-500 hover:underline mr-2">Edit</button>
                        <button class="btnDelete text-red-500 hover:underline">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="bookModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-lg shadow p-6">
        <h2 id="modalTitle" class="text-xl font-bold mb-4">Add Book</h2>
        <form id="bookForm">
            @csrf
            <input type="hidden" name="id" id="bookId">

            <div class="mb-4">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="book_title" id="bookTitle" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="book_description" id="bookDescription" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">File Name</label>
                <input type="text" name="file_name" id="fileName" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Classification</label>
                <select name="classification_id" id="classificationId" class="w-full border rounded px-3 py-2">
                    @foreach($classifications as $class)
                        <option value="{{ $class->id }}">{{ $class->classification }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <button type="button" id="btnClose" class="mr-2 bg-gray-300 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    // Open modal Add
    $("#btnAdd").on("click", function () {
        $("#modalTitle").text("Add Book");
        $("#bookForm")[0].reset();
        $("#bookId").val('');
        $("#bookModal").removeClass("hidden");
    });

    // Close modal
    $("#btnClose").on("click", function () {
        $("#bookModal").addClass("hidden");
    });

    // Submit form (Create / Update)
    $("#bookForm").on("submit", function (e) {
        e.preventDefault();
        let id = $("#bookId").val();
        let url = id ? `/admin/books/${id}` : `/admin/books`;
        let method = id ? "PUT" : "POST";

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function (res) {
                location.reload(); // refresh page after save
            },
            error: function (xhr) {
                alert("Error occurred");
            }
        });
    });

    // Edit
    $(".btnEdit").on("click", function () {
        let tr = $(this).closest("tr");
        let id = tr.data("id");

        $.get(`/admin/books/${id}`, function (book) {
            $("#modalTitle").text("Edit Book");
            $("#bookId").val(book.id);
            $("#bookTitle").val(book.book_title);
            $("#bookDescription").val(book.book_description);
            $("#fileName").val(book.file_name);
            $("#classificationId").val(book.classification_id);
            $("#bookModal").removeClass("hidden");
        });
    });

    // Delete
    $(".btnDelete").on("click", function () {
        if (!confirm("Are you sure to delete this book?")) return;
        let id = $(this).closest("tr").data("id");

        $.ajax({
            url: `/admin/books/${id}`,
            type: "DELETE",
            data: {_token: "{{ csrf_token() }}"},
            success: function () {
                location.reload();
            }
        });
    });
});
</script>
@endpush
