@extends('app')
@section('title', 'Books Management')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Books Management</h1>

    <!-- Add Book Button -->
    <button id="addBookBtn" 
        class="mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        + Add Book
    </button>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full table-auto border-collapse" id="booksTable">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">File</th>
                    <th class="px-4 py-2">Classification</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="bookModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <h2 class="text-xl font-semibold mb-4" id="modalTitle">Add Book</h2>

        <form id="bookForm">
            @csrf
            <input type="hidden" id="book_id" name="book_id">

            <!-- Title -->
            <div class="mb-3">
                <label class="block text-sm font-medium">Book Title</label>
                <input type="text" id="book_title" name="book_title"
                    class="w-full border rounded px-3 py-2">
            </div>

            <!-- File Name -->
            <div class="mb-3">
                <label class="block text-sm font-medium">File Name</label>
                <input type="text" id="file_name" name="file_name"
                    class="w-full border rounded px-3 py-2">
            </div>

            <!-- Classification -->
            <div class="mb-3">
                <label class="block text-sm font-medium">Classification</label>
                <select id="classification_select" name="classification"
                        class="w-full border rounded px-3 py-2">
                    <option value="">-- Select classification --</option>
                    @foreach($classifications as $c)
                        <option value="{{ $c->classification }}">{{ $c->classification }}</option>
                    @endforeach
                    <option value="__new__">+ Add new classification</option>
                </select>
                <!-- Hidden input for new classification -->
                <input type="text" id="new_classification" 
                       class="w-full border rounded px-3 py-2 mt-2 hidden" 
                       placeholder="Enter new classification">
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="block text-sm font-medium">Description</label>
                <textarea id="book_description" name="book_description"
                          class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" id="cancelBtn"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('bookModal');
    const addBookBtn = document.getElementById('addBookBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const form = document.getElementById('bookForm');
    const classificationSelect = document.getElementById('classification_select');
    const newClassificationInput = document.getElementById('new_classification');

    // Show modal
    addBookBtn.addEventListener('click', () => {
        form.reset();
        document.getElementById('book_id').value = '';
        document.getElementById('modalTitle').innerText = "Add Book";
        modal.classList.remove('hidden');
    });

    // Hide modal
    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Toggle "new classification" input
    classificationSelect.addEventListener('change', function () {
        if (this.value === '__new__') {
            newClassificationInput.classList.remove('hidden');
        } else {
            newClassificationInput.classList.add('hidden');
        }
    });

    // Submit form via AJAX
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        let classification = classificationSelect.value;
        if (classification === '__new__') {
            classification = newClassificationInput.value;
        }

        let bookId = document.getElementById('book_id').value;
        let url = bookId ? `/admin/books/${bookId}` : `/admin/books`;
        let method = bookId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                book_title: document.getElementById('book_title').value,
                file_name: document.getElementById('file_name').value,
                book_description: document.getElementById('book_description').value,
                classification: classification,
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                modal.classList.add('hidden');
                loadBooks();
            }
        })
        .catch(err => console.error(err));
    });

    // Load books
    function loadBooks() {
        fetch(`/admin/books`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#booksTable tbody');
                tbody.innerHTML = '';
                data.forEach((book, i) => {
                    tbody.innerHTML += `
                        <tr class="border-t">
                            <td class="px-4 py-2">${i+1}</td>
                            <td class="px-4 py-2">${book.book_title}</td>
                            <td class="px-4 py-2">${book.file_name}</td>
                            <td class="px-4 py-2">${book.classification?.classification ?? '-'}</td>
                            <td class="px-4 py-2">${book.book_description ?? ''}</td>
                            <td class="px-4 py-2">
                                <button class="text-blue-500 editBtn" data-id="${book.id}">Edit</button>
                                <button class="text-red-500 deleteBtn" data-id="${book.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            });
    }

    loadBooks();
});
</script>
@endpush
