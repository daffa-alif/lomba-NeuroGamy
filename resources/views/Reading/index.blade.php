@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row h-screen bg-white">
    <!-- PDF Preview Section -->
    <div class="w-full md:w-3/4 flex flex-col bg-gray-800">
        <!-- PDF Container -->
        <div id="pdf-container" class="flex-1 overflow-auto flex justify-center items-start p-6">
            <canvas id="pdf-canvas" class="shadow-2xl rounded-md"></canvas>
        </div>

        <!-- Page Navigation -->
        <div class="p-4 bg-white flex justify-center items-center gap-4 border-t border-gray-200 shadow-md">
            <button id="prev-page" class="bg-black text-white py-2 px-6 rounded-md hover:bg-gray-800 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                &larr; Prev
            </button>
            <span class="font-bold text-gray-800 text-lg">
                Page <span id="page-num">1</span> of <span id="page-count">?</span>
            </span>
            <button id="next-page" class="bg-black text-white py-2 px-6 rounded-md hover:bg-gray-800 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                Next &rarr;
            </button>

            <!-- Submit Form -->
            <form id="submit-form" 
                  action="{{ route('scorelogs.store') }}" 
                  method="POST" 
                  class="inline ml-6">
                @csrf
                <input type="hidden" name="books_id" value="{{ $book->id }}">
                <input type="hidden" name="title" value="{{ $book->book_title }}">
                <input type="hidden" id="pages-read" name="pages" value="1">
                <button type="submit" 
                        id="submit-reading" 
                        class="hidden bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-md shadow transition">
                    Finish Reading
                </button>
            </form>
        </div>
    </div>

    <!-- AI Summary Section -->
    <div class="w-full md:w-1/4 p-6 bg-gray-50 border-l border-gray-200">
        <div class="bg-white rounded-xl shadow-2xl p-6 h-full flex flex-col">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                AI Summary
            </h2>

            <div id="summary-output" class="text-gray-700 flex-1 overflow-y-auto text-base leading-relaxed pr-2 custom-scrollbar">
                <p class="text-gray-500">Click the button below to generate a summary of the current page.</p>
            </div>

            <button id="summarize-btn" 
                    class="mt-4 w-full bg-black text-white py-3 px-8 rounded-md hover:bg-gray-800 transition font-semibold text-md">
                Summarize This Page
            </button>
        </div>
    </div>
</div>

<!-- PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
let pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 1.5, // Increased scale for better readability
    canvas = document.getElementById("pdf-canvas"),
    ctx = canvas.getContext("2d");

const url = "{{ asset('storage/' . $book->file_name) }}";

const maxPages = {{ $maxPages }};
const totalPages = {{ $totalPages }};
const submitBtn = document.getElementById('submit-reading');
const pagesReadInput = document.getElementById('pages-read');
const prevBtn = document.getElementById('prev-page');
const nextBtn = document.getElementById('next-page');

let maxPageReached = 1;

// Load PDF
pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
    pdfDoc = pdfDoc_;
    document.getElementById('page-count').textContent = pdfDoc.numPages;
    renderPage(pageNum);
});

function renderPage(num) {
    pageRendering = true;
    prevBtn.disabled = (num <= 1);
    nextBtn.disabled = (num >= pdfDoc.numPages);

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

            if (num > maxPageReached) {
                maxPageReached = num;
                pagesReadInput.value = maxPageReached;
            }

            afterRenderPage(num);

            if (pageNumPending !== null) {
                renderPage(pageNumPending);
                pageNumPending = null;
            }
        });
    });
}

function queueRenderPage(num) {
    if (pageRendering) {
        pageNumPending = num;
    } else {
        renderPage(num);
    }
}

function afterRenderPage(num) {
    if (num >= maxPages) {
        submitBtn.classList.remove('hidden');
    } else {
        submitBtn.classList.add('hidden');
    }
}

// Navigation Events
prevBtn.addEventListener('click', function() {
    if (pageNum <= 1) return;
    pageNum--;
    queueRenderPage(pageNum);
});

nextBtn.addEventListener('click', function() {
    if (pageNum >= pdfDoc.numPages) return;
    pageNum++;
    queueRenderPage(pageNum);
});

// Summarize Event
document.getElementById('summarize-btn').addEventListener('click', function() {
    const output = document.getElementById('summary-output');
    this.disabled = true; // Disable button during request
    output.innerHTML = `<div class="flex items-center text-blue-600 font-semibold"><svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating summary...</div>`;

    fetch("{{ route('reading.summarize', $book->id) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        body: JSON.stringify({ page: pageNum })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        return res.json();
    })
    .then(data => {
        if (data.output) {
            output.innerHTML = "<p>" + data.output.replace(/\n/g, "<br>") + "</p>";
        } else {
            output.innerHTML = `<p class='text-red-600 font-semibold'>⚠️ Failed to generate summary.</p>`;
        }
    })
    .catch(err => {
        output.innerHTML = `<p class='text-red-600 font-semibold'>❌ An error occurred. Please try again.</p>`;
    })
    .finally(() => {
        document.getElementById('summarize-btn').disabled = false; // Re-enable button
    });
});
</script>
<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection
