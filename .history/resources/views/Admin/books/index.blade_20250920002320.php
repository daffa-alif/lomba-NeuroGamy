@extends('Admin.AdminLayouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10">

    <h2 class="text-2xl font-bold mb-6">📚 Books</h2>

   {{-- Create Form --}}
   <form id="create-book-form" class="grid grid-cols-2 gap-4 mb-8 p-6 bg-white shadow rounded-lg" enctype="multipart/form-data">
        @csrf
        <select name="classification_id" id="classification_id" 
            class="border rounded-lg px-3 py-2 col-span-2 focus:ring-2 focus:ring-blue-500">
            <option value="">-- Select Classification --</option>
            @foreach($classifications as $c)
                <option value="{{ $c->id }}">{{ $c->classification }}</option>
            @endforeach
        </select>

        <input type="text" name="book_title" id="book_title" placeholder="Book title"
               class="border rounded-lg px-3 py-2 col-span-2 focus:ring-2 focus:ring-blue-500">

        <input type="file" name="file_name" id="file_name" accept="application/pdf"
               class="border rounded-lg px-3 py-2 col-span-2 focus:ring-2 focus:ring-blue-500">

        <textarea name="book_description" id="book_description" rows="2" 
                  placeholder="Book description (optional)"
                  class="border rounded-lg px-3 py-2 col-span-2 focus:ring-2 focus:ring-blue-500"></textarea>

        <button type="submit" 
            class="col-span-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:opacity-90 transition">
            ➕ Add Book
        </button>
    </form>

    {{-- Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Title</th>
                    <th class="p-3">Classification</th>
                    <th class="p-3">File</th>
                    <th class="p-3">Description</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody id="book-table">
                @foreach($books as $book)
                    <tr id="row-{{ $book->id }}" class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $loop->iteration }}</td>
                        <td class="p-3 font-semibold">{{ $book->book_title }}</td>
                        <td class="p-3">{{ $book->classification->classification ?? '-' }}</td>
                 <td class="p-3">
    @if($book->file_name)
        <a href="{{ route('books.file', $book->file_name) }}" 
           target="_blank" 
           class="text-blue-600 underline hover:text-blue-800">
           Preview PDF
        </a>
    @else
        -
    @endif
</td>

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
</div>

{{-- PDF Preview Modal --}}
<div id="pdfModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white w-11/12 md:w-3/4 lg:w-2/3 rounded-lg shadow-lg relative">
        <button id="closeModal" 
            class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">X</button>
        <iframe id="pdfFrame" src="" class="w-full h-[80vh] rounded-b-lg"></iframe>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    // AJAX Create
    $('#create-book-form').on('submit', function(e){
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('books.store') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){
                if(res.success){
                    let b = res.data;
                    let newRow = `
                        <tr id="row-${b.id}" class="border-b hover:bg-gray-50">
                            <td class="p-3">NEW</td>
                            <td class="p-3 font-semibold">${b.book_title}</td>
                            <td class="p-3">${b.classification.classification}</td>
                            <td class="p-3">
                                ${b.file_name 
                                    ? `<button type="button" data-file="/storage/books/${b.file_name}" class="preview-btn text-blue-600 hover:underline">Preview</button>` 
                                    : '-'}
                            </td>
                            <td class="p-3">${b.book_description ?? '-'}</td>
                            <td class="p-3 flex gap-2">
                                <a href="/admin/books/${b.id}/edit" 
                                   class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                                <button data-id="${b.id}" 
                                   class="delete-btn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                            </td>
                        </tr>`;
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

    // PDF Preview
    $(document).on('click', '.preview-btn', function(){
        let file = $(this).data('file');
        $('#pdfFrame').attr('src', file);
        $('#pdfModal').removeClass('hidden');
    });

    $('#closeModal').on('click', function(){
        $('#pdfModal').addClass('hidden');
        $('#pdfFrame').attr('src', '');
    });
});
</script>
@endsection
