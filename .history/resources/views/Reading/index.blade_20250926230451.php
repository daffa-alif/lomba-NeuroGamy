@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    <!-- PDF Preview -->
    <div class="w-2/3 border-r flex flex-col">
        <!-- Tempat render PDF -->
        <div id="pdf-container" class="flex-1 overflow-auto flex items-center justify-center bg-gray-100">
            <canvas id="pdf-canvas"></canvas>
        </div>

        <!-- Navigasi Halaman -->
        <div class="p-2 bg-gray-200 flex justify-center items-center gap-4">
            <button id="prev-page" class="bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500">
                Prev
            </button>
            <span>Halaman <span id="page-num">1</span> / <span id="page-count">?</span></span>
            <button id="next-page" class="bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500">
                Next
            </button>
        </div>
    </div>

    <!-- Ringkasan AI -->
    <div class="w-1/3 p-4 overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Ringkasan AI</h2>

        <div id="summary-output" class="text-gray-700 mb-4">
            <p>Belum ada ringkasan.</p>
        </div>

        <button id="summarize-btn" 
                class="bg-blue-600 text-white px-4 py-2 rounded w-full">
            Ringkas Halaman Ini
        </button>
    </div>
</div>

<!-- PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
let pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 1.2,
    canvas = document.getElementById("pdf-canvas"),
    ctx = canvas.getContext("2d");

const url = "{{ asset('storage/' . $book->file_name) }}";

// Load PDF
pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
    pdfDoc = pdfDoc_;
    document.getElementById('page-count').textContent = pdfDoc.numPages;
    renderPage(pageNum);
});

// Render halaman tertentu
function renderPage(num) {
    pageRendering = true;
    pdfDoc.getPage(num).then(function(page) {
        let viewport = page.getViewport({ scale: scale });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        let renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };
        let renderTask = page.render(renderContext);

        renderTask.promise.then(function() {
            pageRendering = false;
            document.getElementById('page-num').textContent = num;
            if (pageNumPending !== null) {
                renderPage(pageNumPending);
                pageNumPending = null;
            }
        });
    });
}

// Jika sedang render, antri dulu
function queueRenderPage(num) {
    if (pageRendering) {
        pageNumPending = num;
    } else {
        renderPage(num);
    }
}

// Tombol prev
document.getElementById('prev-page').addEventListener('click', function() {
    if (pageNum <= 1) return;
    pageNum--;
    queueRenderPage(pageNum);
});

// Tombol next
document.getElementById('next-page').addEventListener('click', function() {
    if (pageNum >= pdfDoc.numPages) return;
    pageNum++;
    queueRenderPage(pageNum);
});

// Summarize current page
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
            page: pageNum
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
