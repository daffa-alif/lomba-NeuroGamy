@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <h2 class="text-2xl font-bold mb-6">Book Classifications</h2>

    {{-- Create Form --}}
    <form id="create-form" class="flex gap-3 mb-6">
        @csrf
        <input type="text" name="classification" id="classification" placeholder="New classification..."
               class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-300">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add
        </button>
    </form>

    {{-- Table --}}
    <table class="w-full border border-gray-300 rounded-lg">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">Classification</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody id="classification-table">
            @foreach($classifications as $classification)
                <tr id="row-{{ $classification->id }}" class="border-b">
                    <td class="p-3">{{ $loop->iteration }}</td>
                    <td class="p-3">{{ $classification->classification }}</td>
                    <td class="p-3 flex gap-2">
                        <a href="{{ route('classifications.edit', $classification) }}" 
                           class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                        <button data-id="{{ $classification->id }}" 
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
                        <tr id="row-${res.data.id}" class="border-b">
                            <td class="p-3">NEW</td>
                            <td class="p-3">${res.data.classification}</td>
                            <td class="p-3 flex gap-2">
                                <a href="/admin/classifications/${res.data.id}/edit" 
                                   class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                                <button data-id="${res.data.id}" 
                                   class="delete-btn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                            </td>
                        </tr>
                    `;
                    $('#classification-table').prepend(newRow);
                    $('#classification').val('');
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
        if(confirm("Delete this classification?")){
            $.ajax({
                url: "/admin/classifications/" + id,
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
