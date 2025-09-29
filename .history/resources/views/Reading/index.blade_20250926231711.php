@extends('layouts.app')

@section('content')
<style>
    .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.9);
    }
    .pdf-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="flex h-screen bg-gray-50">
    <!-- PDF Preview -->
    <div class="w-2/3 border-r border-gray-200 flex flex-col bg-white shadow-lg">
        <!-- PDF Container -->
        <div id="pdf-container" class="flex-1 overflow-auto flex items-center justify-center bg-gradient-to-br from-gray-50 to-blue-50 p-4">
            <div class="pdf-shadow rounded-lg bg-white">
                <canvas id="pdf-canvas"></canvas>
            </div>
        </div>

        <!-- Enhanced Navigation -->
        <div class="glass-effect p-4 border-t border-gray-200 flex justify-center items-center gap-6">
            <button id="prev-page" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                ← Previous
            </button>
            
            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-lg shadow-sm border">
                <span class="text-gray-600 font-medium">Page</span>
                <span id="page-num" class="font-bold text-blue-600">1</span>
                <span class="text-gray-400">/</span>
                <span id="page-count" class="font-medium text-gray-700">?</span>
            </div>
            
            <button id="next-page" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                Next →
            </button>
        </div>
    </div>

    <!-- Enhanced AI Summary -->
    <div class="w-1/3 bg-white shadow-lg flex flex-col">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold mb-2 text-gray-800 flex items-center">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    🧠
                </div>
                AI Summary
            </h2>
            <p class="text-gray-600 text-sm">Intelligent document analysis</p>
        </div>

        <!-- Summary Content -->
        <div class="flex-1 p-6 overflow-y-auto">
            <div id="summary-output" class="text-gray-700 mb-6">
                <div class="text-center py-8 text-gray-500">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        ✨
                    </div>
                    <p class="font-medium mb-2">Ready to analyze</p>
                    <p class="text-sm">Click below to generate insights</p>
                </div>
            </div>
        </div>

        <!-- Enhanced Button -->
        <div class="p-6 border-t border-gray-200 bg-gray-50">
            <button id="summarize-btn" 
                    class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg w-full font-medium transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                ✨ Summarize This Page
            </button>
        </div>
    </div>
</div>

<!-- Your original PDF.js script (unchanged) -->
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
    const button = this;
    
    // Enhanced loading state
    button.innerHTML = "🔄 Analyzing...";
    button.disabled = true;
    
    output.innerHTML = `
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <div class="animate-spin text-xl">⚡</div>
                <div>
                    <p class="text-blue-800 font-medium">AI sedang menganalisis halaman ${pageNum}...</p>
                    <p class="text-blue-600 text-sm">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    `;

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
            output.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <span class="text-green-600 text-xl mr-2">✅</span>
                        <h3 class="font-medium text-green-800">Ringkasan Halaman ${pageNum}</h3>
                    </div>
                    <div class="text-gray-700 leading-relaxed">${data.output}</div>
                </div>
            `;
        } else {
            output.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <span class="text-red-600 text-xl mr-2">❌</span>
                        <p class="text-red-700 font-medium">Gagal menghasilkan ringkasan</p>
                    </div>
                </div>
            `;
        }
    })
    .catch(err => {
        output.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <span class="text-red-600 text-xl mr-2">⚠️</span>
                    <div>
                        <p class="text-red-700 font-medium">Error</p>
                        <p class="text-red-600 text-sm">${err.message}</p>
                    </div>
                </div>
            </div>
        `;
    })
    .finally(() => {
        button.innerHTML = "✨ Summarize This Page";
        button.disabled = false;
    });
});
</script>
@endsection