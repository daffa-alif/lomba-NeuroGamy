@extends('Admin.AdminLayouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Books Management</h1>

    <!-- Add Book Button -->
    <button onclick="openModal()" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Book</button>

    <!-- Books Table -->
    <table class="table-auto w-full mt-6 border">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Classification</th>
                <th class="px-4 py-2">File</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody id="booksTable">
            @foreach($books as $book)
            <tr data-id="{{ $book->id }}">
                <td>{{ $book->book_title }}</td>
                <td>{{ $book->classification->classification ?? '-' }}</td>
                <td>{{ $book->file_name }}</td>
                <td>{{ $book->book_description }}</td>
                <td>
                    <button onclick="editBook({{ $book->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                    <button onclick="deleteBook({{ $book->id }})" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div id="bookModal" class="fixed inset-0 hidden items-center justify-center bg-gray-800 bg-opacity-50">
        <div class="bg-white p-6 rounded shadow w-1/3">
            <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Book</h2>

            <form id="bookForm">
                @csrf
                <input type="hidden" name="id" id="bookId">

                <!-- Classification -->
                <label>Classification</label>
                <select name="classification_id" id="classificationSelect" class="border p-2 w-full mb-3">
                    <option value="">-- Select --</option>
                    @foreach($classifications as $c)
                        <option value="{{ $c->id }}">{{ $c->classification }}</option>
                    @endforeach
                    <option value="new">+ Add New Classification</option>
                </select>
                <input type="text" id="newClassificationInput" placeholder="New classification" class="border p-2 w-full mb-3 hidden">

                <!-- Book Title -->
                <label>Book Title</label>
                <input type="text" name="book_title" id="bookTitle" class="border p-2 w-full mb-3">

                <!-- File -->
                <label>File Name</label>
                <input type="text" name="file_name" id="fileName" class="border p-2 w-full mb-3">

                <!-- Description -->
                <label>Description</label>
                <textarea name="book_description" id="bookDescription" class="border p-2 w-full mb-3"></textarea>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('bookForm').reset();
    document.getElementById('bookId').value = '';
    document.getElementById('modalTitle').innerText = 'Add Book';
    document.getElementById('bookModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('bookModal').classList.add('hidden');
}

// Show new classification input
document.getElementById('classificationSelect').addEventListener('change', function() {
    document.getElementById('newClassificationInput').classList.toggle('hidden', this.value !== 'new');
});

// Save Book (Create/Update)
document.getElementById('bookForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    let id = document.getElementById('bookId').value;
    let formData = new FormData(this);

    // Handle new classification
    if (document.getElementById('classificationSelect').value === 'new') {
        let res = await fetch("{{ route('classifications.store') }}", {
            method: "POST",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}","Content-Type": "application/json"},
            body: JSON.stringify({ classification: document.getElementById('newClassificationInput').value })
        });
        let newC = await res.json();
        formData.set('classification_id', newC.id);
    }

    let url = id ? `/admin/books/${id}` : `/admin/books`;
    let method = id ? "PUT" : "POST";

    let response = await fetch(url, {
        method: method,
        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
        body: formData
    });

    if (response.ok) location.reload();
});

// Edit Book
async function editBook(id) {
    let res = await fetch(`/admin/books/${id}`);
    let book = await res.json();

    document.getElementById('bookId').value = book.id;
    document.getElementById('bookTitle').value = book.book_title;
    document.getElementById('fileName').value = book.file_name;
    document.getElementById('bookDescription').value = book.book_description;
    document.getElementById('classificationSelect').value = book.classification_id;

    document.getElementById('modalTitle').innerText = 'Edit Book';
    document.getElementById('bookModal').classList.remove('hidden');
}

// Delete Book
async function deleteBook(id) {
    if (!confirm("Are you sure?")) return;
    let res = await fetch(`/admin/books/${id}`, {
        method: "DELETE",
        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"}
    });
    if (res.ok) location.reload();
}
</script>
@endsection
