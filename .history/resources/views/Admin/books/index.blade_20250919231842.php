@extends('Admin.AdminLayouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Books Management</h1>

    <!-- Button -->
    <button onclick="openModal()" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Book</button>

    <!-- Table -->
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
            <tr>
                <td class="border px-4 py-2">{{ $book->book_title }}</td>
                <td class="border px-4 py-2">{{ $book->classification->classification ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $book->file_name }}</td>
                <td class="border px-4 py-2">{{ $book->book_description }}</td>
                <td class="border px-4 py-2">
                    <button class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                    <button class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div id="bookModal" class="fixed inset-0 hidden items-center justify-center bg-gray-800 bg-opacity-50">
        <div class="bg-white p-6 rounded shadow w-1/3">
            <h2 class="text-xl font-bold mb-4">Add Book</h2>

            <form id="bookForm">
                @csrf
                <!-- Classification -->
                <label class="block">Classification</label>
                <select name="classification_id" id="classificationSelect" class="border p-2 w-full mb-3">
                    <option value="">-- Select --</option>
                    @foreach($classifications as $c)
                        <option value="{{ $c->id }}">{{ $c->classification }}</option>
                    @endforeach
                    <option value="new">+ Add New Classification</option>
                </select>

                <!-- Add classification input (hidden by default) -->
                <input type="text" id="newClassificationInput" placeholder="New classification name" class="border p-2 w-full mb-3 hidden">

                <!-- Book Title -->
                <label class="block">Book Title</label>
                <input type="text" name="book_title" class="border p-2 w-full mb-3">

                <!-- File -->
                <label class="block">File Name</label>
                <input type="text" name="file_name" class="border p-2 w-full mb-3">

                <!-- Description -->
                <label class="block">Description</label>
                <textarea name="book_description" class="border p-2 w-full mb-3"></textarea>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('bookModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('bookModal').classList.add('hidden');
}

// Handle classification select
document.getElementById('classificationSelect').addEventListener('change', function() {
    if (this.value === 'new') {
        document.getElementById('newClassificationInput').classList.remove('hidden');
    } else {
        document.getElementById('newClassificationInput').classList.add('hidden');
    }
});

// Handle form submit
document.getElementById('bookForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    // If new classification is entered, create it first
    if (document.getElementById('classificationSelect').value === 'new') {
        let newClassification = document.getElementById('newClassificationInput').value;

        let res = await fetch("{{ route('classifications.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ classification: newClassification })
        });

        let data = await res.json();
        formData.set('classification_id', data.id);
    }

    // Store book
    let response = await fetch("{{ route('books.store') }}", {
        method: "POST",
        body: formData
    });

    if (response.ok) {
        location.reload();
    }
});
</script>
@endsection
