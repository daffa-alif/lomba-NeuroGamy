@extends('Admin.AdminLayouts.app')

@section('content')
<div class="p-6 space-y-12">

    <!-- Add Book Form Card -->
    <div class="bg-white p-10 rounded-xl shadow-2xl w-full max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-8">Add New Book</h2>
        <form id="create-book-form" class="space-y-5" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="book_title" class="block mb-2 font-bold text-gray-800">Book Title</label>
                <input type="text" name="book_title" id="book_title" placeholder="e.g., The Psychology of Money"
                       class="w-full p-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition" required>
            </div>
            
            <div>
                <label for="classification_id" class="block mb-2 font-bold text-gray-800">Category</label>
                <select name="classification_id" id="classification_id" 
                        class="w-full p-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition" required>
                    <option value="">-- Select a Category --</option>
                    @foreach($classifications as $c)
                        <option value="{{ $c->id }}">{{ $c->classification }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                 <label for="book_description" class="block mb-2 font-bold text-gray-800">Description <span class="font-normal text-gray-500">(Optional)</span></label>
                <textarea name="book_description" id="book_description" rows="3" 
                          placeholder="A short summary of the book..."
                          class="w-full p-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition"></textarea>
            </div>
            
            <div>
                <label for="file_name" class="block mb-2 font-bold text-gray-800">Book File (PDF)</label>
                <input type="file" name="file_name" id="file_name" accept="application/pdf"
                       class="w-full text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition" required>
            </div>

            <div class="pt-4">
                <button type="submit" 
                        class="w-full bg-black text-white py-3 px-8 rounded-md hover:bg-gray-800 transition font-semibold text-lg">
                    Add Book
                </button>
            </div>
        </form>
    </div>

    <!-- Books Table Card -->
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-6xl mx-auto">
         <h2 class="text-3xl font-bold mb-8">Manage Books</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="p-4">#</th>
                        <th class="p-4">Title</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">File</th>
                        <th class="p-4">Description</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="book-table" class="text-gray-700">
                    @foreach($books as $book)
                        <tr id="row-{{ $book->id }}" class="border-b hover:bg-gray-50">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-bold text-gray-800">{{ $book->book_title }}</td>
                            <td class="p-4">{{ $book->classification->classification ?? '-' }}</td>
                            <td class="p-4">
                                @if($book->file_name)
                                    <a href="{{ route('books.file', $book->file_name) }}" 
                                       target="_blank" 
                                       class="text-blue-600 font-semibold underline hover:text-blue-800">
                                        Preview
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4 text-sm max-w-xs truncate">{{ $book->book_description ?? '-' }}</td>
                            <td class="p-4 flex gap-2 justify-center">
                                <a href="{{ route('books.edit', $book) }}" 
                                   class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 font-semibold text-sm">Edit</a>
                                <button data-id="{{ $book->id }}" 
                                        class="delete-btn px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold text-sm">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- PDF Preview Modal --}}
<div id="pdfModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white w-11/12 md:w-3/4 lg:w-2/3 rounded-lg shadow-lg relative">
        <button id="closeModal" 
                class="absolute -top-4 -right-4 bg-red-600 text-white w-10 h-10 rounded-full hover:bg-red-700 font-bold text-lg z-10">X</button>
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
                            <td class="p-4">NEW</td>
                            <td class="p-4 font-bold text-gray-800">${b.book_title}</td>
                            <td class="p-4">${b.classification.classification}</td>
                            <td class="p-4">
                                ${b.file_name 
                                    ? `<button type="button" data-file="/storage/books/${b.file_name}" class="preview-btn text-blue-600 font-semibold underline hover:text-blue-800">Preview</button>` 
                                    : '-'}
                            </td>
                            <td class="p-4 text-sm max-w-xs truncate">${b.book_description ?? '-'}</td>
                            <td class="p-4 flex gap-2 justify-center">
                                <a href="/admin/books/${b.id}/edit" 
                                   class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 font-semibold text-sm">Edit</a>
                                <button data-id="${b.id}" 
                                        class="delete-btn px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold text-sm">Delete</button>
                            </td>
                        </tr>`;
                    $('#book-table').prepend(newRow);
                    $('#create-book-form')[0].reset();
                }
            },
            error: function(xhr){
                // A more user-friendly error display could be implemented here
                alert(xhr.responseJSON ? xhr.responseJSON.message : "An error occurred.");
            }
        });
    });

    // AJAX Delete
    $(document).on('click', '.delete-btn', function(){
        let id = $(this).data('id');
        if(confirm("Are you sure you want to delete this book?")){
            $.ajax({
                url: "/admin/books/" + id,
                method: "DELETE",
                data: {_token: "{{ csrf_token() }}"},
                success: function(res){
                    if(res.success){
                        $('#row-' + id).fadeOut(300, function() { $(this).remove(); });
                    }
                }
            });
        }
    });

    // PDF Preview Modal Logic
    $(document).on('click', '.preview-btn', function(){
        let fileUrl = $(this).data('file');
        $('#pdfFrame').attr('src', fileUrl);
        $('#pdfModal').removeClass('hidden');
    });

    $('#closeModal').on('click', function(){
        $('#pdfModal').addClass('hidden');
        $('#pdfFrame').attr('src', ''); // Clear src to stop loading
    });
});
</script>
@endsection
