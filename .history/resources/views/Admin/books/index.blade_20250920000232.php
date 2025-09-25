@extends('Admin.AdminLayouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10">

    <h2 class="text-2xl font-bold mb-6">Books</h2>

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


    {{-- Table --}}
    <table class="w-full border border-gray-300 rounded-lg">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">Title</th>
                <th class="p-3 text-left">Classification</th>
                <th class="p-3 text-left">File</th>
                <th class="p-3 text-left">Description</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody id="book-table">
            @foreach($books as $book)
                <tr id="row-{{ $book->id }}" class="border-b">
                    <td class="p-3">{{ $loop->iteration }}</td>
                    <td class="p-3">{{ $book->book_title }}</td>
                    <td class="p-3">{{ $book->classification->classification ?? '-' }}</td>
                    <td class="p-3">{{ $book->file_name ?? '-' }}</td>
                    <td class="p-3">{{ $book->book_description ?? '-' }}</td>
                    <td class="p-3 flex gap-2">
                        <a href="{{ route('books.edit', $book) }}" 
                           class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                        <button data-id="{{ $book->id }}" 
                                class="delete-btn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    // AJAX Create
    $('#create-book-form').on('submit', function(e){
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('books.store') }}",
            method: "POST",
            data: formData,
            success: function(res){
                if(res.success){
                    let b = res.data;
                    let newRow = `
                        <tr id="row-${b.id}" class="border-b">
                            <td class="p-3">NEW</td>
                            <td class="p-3">${b.book_title}</td>
                            <td class="p-3">${b.classification.classification}</td>
                            <td class="p-3">${b.file_name ?? '-'}</td>
                            <td class="p-3">${b.book_description ?? '-'}</td>
                            <td class="p-3 flex gap-2">
                                <a href="/admin/books/${b.id}/edit" 
                                   class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                                <button data-id="${b.id}" 
                                   class="delete-btn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                            </td>
                        </tr>
                    `;
                    $('#book-table').prepend(newRow);
                    $('#create-book-form')[0].reset();
                }
            },
            error: function(xhr){
                alert(xhr.responseJSON.message);
            }
        });
    });

    // AJAX Delete
    $(document).on('click', '.delete-btn', function(){
        let id = $(this).data('id');
        if(confirm("Delete this book?")){
            $.ajax({
                url: "/admin/books/" + id,
                method: "DELETE",
                data: {_token: "{{ csrf_token() }}"},
                success: function(res){
                    if(res.success){
                        $('#row-' + id).remove();
                    }
                }
            });
        }
    });
});
</script>
@endsection
