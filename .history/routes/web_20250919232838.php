@extends('Admin.AdminLayouts.app')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Books Management</h2>

    <form id="bookForm">
        @csrf
        <div class="mb-3">
            <label class="block text-sm font-medium">Title</label>
            <input type="text" name="title" class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Classification</label>
            <div class="flex gap-2">
                <select name="classification_id" id="classificationSelect" class="w-full border rounded p-2">
                    <option value="">-- Select Classification --</option>
                    @foreach($classifications as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <button type="button" id="btnAddClassification" class="bg-blue-500 text-white px-3 rounded">+</button>
            </div>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">File Name</label>
            <input type="text" name="file_name" class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="w-full border rounded p-2"></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    </form>

    {{-- Table --}}
    <table class="mt-6 w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Title</th>
                <th class="p-2 border">Classification</th>
                <th class="p-2 border">File</th>
                <th class="p-2 border">Description</th>
                <th class="p-2 border">Actions</th>
            </tr>
        </thead>
        <tbody id="bookTableBody">
            {{-- Ajax inject --}}
        </tbody>
    </table>
</div>

{{-- Modal Classification --}}
<div id="classificationModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-3">Add Classification</h3>
        <form id="classificationForm">
            @csrf
            <input type="text" name="name" class="w-full border rounded p-2 mb-3" placeholder="Classification name">
            <div class="flex justify-end gap-2">
                <button type="button" id="closeModal" class="px-3 py-1 border rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // Open modal
    $('#btnAddClassification').on('click', function() {
        $('#classificationModal').removeClass('hidden');
    });

    // Close modal
    $('#closeModal').on('click', function() {
        $('#classificationModal').addClass('hidden');
    });

    // Submit classification via AJAX
    $('#classificationForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('classifications.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                $('#classificationSelect').append(`<option value="${res.id}" selected>${res.name}</option>`);
                $('#classificationModal').addClass('hidden');
                $('#classificationForm')[0].reset();
            }
        });
    });

    // Submit book form
    $('#bookForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('books.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                loadBooks();
                $('#bookForm')[0].reset();
            }
        });
    });

    // Load books into table
    function loadBooks() {
        $.get("{{ route('books.index') }}", function(data) {
            $('#bookTableBody').html(data);
        });
    }
    loadBooks();
});
</script>
@endpush
