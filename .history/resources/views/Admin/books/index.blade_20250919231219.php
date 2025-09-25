@extends('app')

@section('title', 'Books Management')
@section('breadcrumb', 'Books')

@section('content')
<div class="bg-white shadow-lg rounded-xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Books</h2>
        <button id="addBookBtn" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Add Book
        </button>
    </div>

    <!-- Table -->
    <table class="w-full border-collapse bg-white rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="py-3 px-4 text-left">#</th>
                <th class="py-3 px-4 text-left">Title</th>
                <th class="py-3 px-4 text-left">Classification</th>
                <th class="py-3 px-4 text-left">Description</th>
                <th class="py-3 px-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody id="booksTableBody" class="text-gray-600">
            <!-- AJAX Data -->
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="bookModal" 
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative">
        <h3 class="text-lg font-semibold mb-4" id="modalTitle">Add Book</h3>
        
        <form id="bookForm">
            @csrf
            <input type="hidden" id="book_id" name="book_id">

            <div class="mb-4">
                <label class="block text-gray-700">Book Title</label>
                <input type="text" id="book_title" name="book_title"
                       class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Classification ID</label>
                <input type="number" id="classification_id" name="classification_id"
                       class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Description</label>
                <textarea id="book_description" name="book_description"
                          class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-300"></textarea>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    loadBooks();

    // Show modal
    $("#addBookBtn").on("click", function() {
        $("#modalTitle").text("Add Book");
        $("#bookForm")[0].reset();
        $("#book_id").val("");
        $("#bookModal").removeClass("hidden");
    });

    // Hide modal
    $("#closeModal").on("click", function() {
        $("#bookModal").addClass("hidden");
    });

    // Save or Update
    $("#bookForm").on("submit", function(e) {
        e.preventDefault();

        let id = $("#book_id").val();
        let url = id ? `/admin/books/${id}` : "{{ route('books.store') }}";
        let method = id ? "PUT" : "POST";

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function() {
                $("#bookModal").addClass("hidden");
                loadBooks();
            }
        });
    });

    // Load books
    function loadBooks() {
        $.get("{{ route('books.index') }}", function(data) {
            let rows = "";
            data.forEach((book, i) => {
                rows += `
                    <tr>
                        <td class="py-2 px-4">${i+1}</td>
                        <td class="py-2 px-4">${book.book_title}</td>
                        <td class="py-2 px-4">${book.classification_id}</td>
                        <td class="py-2 px-4">${book.book_description ?? ""}</td>
                        <td class="py-2 px-4 text-center space-x-2">
                            <button class="editBtn px-2 py-1 bg-yellow-500 text-white rounded" data-id="${book.id}">Edit</button>
                            <button class="deleteBtn px-2 py-1 bg-red-500 text-white rounded" data-id="${book.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $("#booksTableBody").html(rows);
        });
    }

    // Edit
    $(document).on("click", ".editBtn", function() {
        let id = $(this).data("id");
        $.get(`/admin/books/${id}`, function(book) {
            $("#modalTitle").text("Edit Book");
            $("#book_id").val(book.id);
            $("#book_title").val(book.book_title);
            $("#classification_id").val(book.classification_id);
            $("#book_description").val(book.book_description);
            $("#bookModal").removeClass("hidden");
        });
    });

    // Delete
    $(document).on("click", ".deleteBtn", function() {
        let id = $(this).data("id");
        if (confirm("Are you sure?")) {
            $.ajax({
                url: `/admin/books/${id}`,
                type: "DELETE",
                data: {_token: "{{ csrf_token() }}"},
                success: function() {
                    loadBooks();
                }
            });
        }
    });
});
</script>
@endsection
