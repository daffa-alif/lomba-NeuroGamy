@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    <!-- PDF Preview -->
    <div class="w-2/3 border-r">
    <iframe src="{{ route('reading.file', $book->id) }}" class="w-full h-full"></iframe>

</div>

    <!-- Ringkasan AI -->
    <div class="w-1/3 p-4 overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Ringkasan AI</h2>

        <div id="summary-output" class="text-gray-700 mb-4">
            <p>Belum ada ringkasan.</p>
        </div>

        <button id="summarize-btn" 
                class="bg-blue-600 text-white px-4 py-2 rounded">
            Generate Ringkasan
        </button>
    </div>
</div>

<script>
document.getElementById('summarize-btn').addEventListener('click', function() {
    const output = document.getElementById('summary-output');
    output.innerHTML = "<p><em>Meminta ringkasan ke AI...</em></p>";

    fetch("{{ route('reading.summarize', $book->id) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        body: JSON.stringify({
            prompt: "Buat ringkasan singkat dari buku berjudul '{{ $book->book_title }}'."
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.output) {
            output.innerHTML = "<p>" + data.output + "</p>";
        } else {
            output.innerHTML = "<p class='text-red-500'>Gagal menghasilkan ringkasan.</p>";
        }
    })
    .catch(err => {
        output.innerHTML = "<p class='text-red-500'>Error: " + err.message + "</p>";
    });
});
</script>
@endsection
