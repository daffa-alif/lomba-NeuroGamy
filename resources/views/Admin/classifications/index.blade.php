@extends('Admin.AdminLayouts.app')

@section('content')
<div class="p-6 space-y-12">

    <!-- Add Classification Form Card -->
    <div class="bg-white p-10 rounded-xl shadow-2xl w-full max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold mb-8">Add New Category</h2>
        <form id="create-form" class="flex items-center gap-4">
            @csrf
            <div class="flex-grow">
                <label for="classification" class="sr-only">New classification</label>
                <input type="text" name="classification" id="classification" placeholder="e.g., Psychology, Finance, Novel..."
                       class="w-full p-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition" required>
            </div>
            <button type="submit" class="bg-black text-white py-3 px-8 rounded-md hover:bg-gray-800 transition font-semibold">
                Add
            </button>
        </form>
    </div>

    <!-- Classifications Table Card -->
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-8">Manage Categories</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="p-4">#</th>
                        <th class="p-4">Classification Name</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="classification-table" class="text-gray-700">
                    @foreach($classifications as $classification)
                        <tr id="row-{{ $classification->id }}" class="border-b hover:bg-gray-50">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-bold text-gray-800">{{ $classification->classification }}</td>
                            <td class="p-4 flex gap-2 justify-center">
                                <a href="{{ route('classifications.edit', $classification) }}" 
                                   class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 font-semibold text-sm">Edit</a>
                                <button data-id="{{ $classification->id }}" 
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    // AJAX Create
    $('#create-form').on('submit', function(e){
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('classifications.store') }}",
            method: "POST",
            data: formData,
            success: function(res){
                if(res.success){
                    let newRow = `
                        <tr id="row-${res.data.id}" class="border-b hover:bg-gray-50">
                            <td class="p-4">NEW</td>
                            <td class="p-4 font-bold text-gray-800">${res.data.classification}</td>
                            <td class="p-4 flex gap-2 justify-center">
                                <a href="/admin/classifications/${res.data.id}/edit" 
                                   class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 font-semibold text-sm">Edit</a>
                                <button data-id="${res.data.id}" 
                                        class="delete-btn px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold text-sm">Delete</button>
                            </td>
                        </tr>
                    `;
                    $('#classification-table').prepend(newRow);
                    $('#classification').val('');
                }
            },
            error: function(xhr){
                alert(xhr.responseJSON ? xhr.responseJSON.message : "An error occurred.");
            }
        });
    });

    // AJAX Delete
    $(document).on('click', '.delete-btn', function(){
        let id = $(this).data('id');
        if(confirm("Are you sure you want to delete this classification?")){
            $.ajax({
                url: "/admin/classifications/" + id,
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
});
</script>
@endsection
