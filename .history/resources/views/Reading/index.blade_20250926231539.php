@extends('layouts.app')

@section('content')
<style>
    .glass-effect {
        backdrop-filter: blur(16px) saturate(180%);
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(209, 213, 219, 0.3);
    }
    .pdf-shadow {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .animate-pulse-custom {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .slide-in {
        animation: slideIn 0.3s ease-out;
    }
</style>

<!-- Header -->
<div class="glass-effect sticky top-0 z-50 px-6 py-4 mb-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Smart PDF Reader</h1>
                <p class="text-sm text-gray-600">Intelligent document analysis</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <button id="fullscreen-btn" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<div class="flex h-screen">
    <!-- PDF Preview -->
    <div class="flex-1 border-r border-gray-200 flex flex-col bg-white rounded-l-lg shadow-lg">
        <!-- Enhanced Toolbar -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-tl-lg">
            <div class="flex items-center justify-between">
                <!-- Zoom Controls -->
                <div class="flex items-center space-x-3">
                    <button id="zoom-out" class="p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path>
                        </svg>
                    </button>
                    <span id="zoom-level" class="text-sm font-medium text-gray-700 min-w-[60px] text-center px-2 py-1 bg-white rounded border">120%</span>
                    <button id="zoom-in" class="p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </button>
                    <button id="fit-width" class="px-3 py-2 text-xs bg-white rounded-lg hover:bg-gray-100 transition-colors border">
                        Fit Width
                    </button>
                </div>

                <!-- Enhanced Page Navigation -->
                <div class="flex items-center space-x-4">
                    <button id="prev-page" class="p-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed bg-white shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    
                    <div class="flex items-center space-x-2 bg-white rounded-lg px-3 py-2 border shadow-sm">
                        <span class="text-sm text-gray-600">Page</span>
                        <input id="page-input" type="number" min="1" value="1" 
                               class="w-12 text-center border-none focus:ring-0 text-sm font-medium">
                        <span class="text-gray-400">/</span>
                        <span id="page-count" class="text-gray-700 font-medium">?</span>
                    </div>
                    
                    <button id="next-page" class="p-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed bg-white shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <!-- View Options -->
                <div class="flex items-center space-x-2">
                    <button id="rotate-left" class="p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                    </button>
                    <button id="rotate-right" class="p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- PDF Display Area -->
        <div id="pdf-container" class="flex-1 overflow-auto flex items-center justify-center bg-gradient-to-br from-gray-50 to-blue-50 p-6 relative">
            <div class="pdf-shadow rounded-lg overflow-hidden bg-white relative">
                <canvas id="pdf-canvas" class="max-w-full h-auto"></canvas>
                
                <!-- Loading Overlay -->
                <div id="loading-overlay" class="hidden absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-3"></div>
                        <p class="text-gray-600 text-sm">Loading page...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="h-2 bg-gray-200 rounded-b-lg">
            <div id="reading-progress" class="h-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-500 rounded-b-lg" style="width: 0%"></div>
        </div>
    </div>

    <!-- Enhanced AI Summary Panel -->
    <div class="w-96 bg-white border-l border-gray-200 flex flex-col rounded-r-lg shadow-lg">
        <!-- Panel Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50 rounded-tr-lg">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-lg font-bold text-gray-800 flex items-center">
                    <div class="p-1 bg-blue-100 rounded mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    AI Summary
                </h2>
                <div class="flex items-center space-x-1">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse-custom"></div>
                    <span class="text-xs text-gray-500">Ready</span>
                </div>
            </div>
            <p class="text-sm text-gray-600">Intelligent document analysis powered by AI</p>
        </div>

        <!-- Summary Content -->
        <div class="flex-1 p-6 overflow-y-auto">
            <div id="summary-output" class="space-y-4">
                <div class="text-center py-12 text-gray-500">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-purple-50 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 font-medium mb-2">Ready to Analyze</p>
                    <p class="text-sm text-gray-500">Click "Summarize" to generate AI insights for the current page</p>
                </div>
            </div>

            <!-- Summary History -->
            <div id="summary-history" class="hidden mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Previous Summaries
                </h3>
                <div id="history-list" class="space-y-3"></div>
            </div>
        </div>

        <!-- Enhanced Action Buttons -->
        <div class="p-6 border-t border-gray-200 space-y-4 bg-gray-50 rounded-br-lg">
            <button id="summarize-btn" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 hover:shadow-lg flex items-center justify-center space-x-3 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span>Summarize Current Page</span>
            </button>
            
            <div class="grid grid-cols-2 gap-3">
                <button id="extract-text-btn" class="bg-white border border-gray-200 text-gray-700 py-2 px-3 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all text-sm flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Extract</span>
                </button>
                <button id="translate-btn" class="bg-white border border-gray-200 text-gray-700 py-2 px-3 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all text-sm flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    <span>Translate</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Your original PDF.js code with enhancements -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
let pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 1.2,
    rotation = 0,
    canvas = document.getElementById("pdf-canvas"),
    ctx = canvas.getContext("2d");

// Your original URL - keeping it intact
const url = "{{ asset('storage/' . $book->file_name) }}";

// Enhanced loading with better UX
function showLoading() {
    document.getElementById('loading-overlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
}

// Load PDF with enhanced feedback
pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
    pdfDoc = pdfDoc_;
    document.getElementById('page-count').textContent = pdfDoc.numPages;
    document.getElementById('page-input').max = pdfDoc.numPages;
    renderPage(pageNum);
    updateProgress();
}).catch(function(error) {
    console.error('Error loading PDF:', error);
    document.getElementById('summary-output').innerHTML = 
        '<div class="text-red-500 p-4 bg-red-50 rounded-lg"><p><strong>Error loading PDF:</strong> ' + error.message + '</p></div>';
});

// Enhanced render function
function renderPage(num) {
    pageRendering = true;
    showLoading();
    
    pdfDoc.getPage(num).then(function(page) {
        let viewport = page.getViewport({ scale: scale, rotation: rotation });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        let renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };
        let renderTask = page.render(renderContext);

        renderTask.promise.then(function() {
            pageRendering = false;
            hideLoading();
            document.getElementById('page-num').textContent = num;
            document.getElementById('page-input').value = num;
            updateProgress();
            updateButtonStates();
            
            if (pageNumPending !== null) {
                renderPage(pageNumPending);
                pageNumPending = null;
            }
        });
    });
}

// Enhanced navigation functions
function queueRenderPage(num) {
    if (pageRendering) {
        pageNumPending = num;
    } else {
        renderPage(num);
    }
}

function updateProgress() {
    if (pdfDoc) {
        const progress = (pageNum / pdfDoc.numPages) * 100;
        document.getElementById('reading-progress').style.width = progress + '%';
    }
}

function updateButtonStates() {
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    
    prevBtn.disabled = pageNum <= 1;
    nextBtn.disabled = pageNum >= pdfDoc.numPages;
    
    if (prevBtn.disabled) {
        prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    if (nextBtn.disabled) {
        nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// Enhanced event listeners
document.getElementById('prev-page').addEventListener('click', function() {
    if (pageNum <= 1) return;
    pageNum--;
    queueRenderPage(pageNum);
});

document.getElementById('next-page').addEventListener('click', function() {
    if (pageNum >= pdfDoc.numPages) return;
    pageNum++;
    queueRenderPage(pageNum);
});

// Page input handler
document.getElementById('page-input').addEventListener('change', function() {
    const newPage = parseInt(this.value);
    if (newPage >= 1 && newPage <= pdfDoc.numPages && newPage !== pageNum) {
        pageNum = newPage;
        queueRenderPage(pageNum);
    } else {
        this.value = pageNum; // Reset to current page if invalid
    }
});

// Zoom controls
document.getElementById('zoom-in').addEventListener('click', function() {
    scale *= 1.2;
    document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
    renderPage(pageNum);
});

document.getElementById('zoom-out').addEventListener('click', function() {
    scale *= 0.8;
    document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
    renderPage(pageNum);
});

document.getElementById('fit-width').addEventListener('click', function() {
    const container = document.getElementById('pdf-container');
    scale = (container.clientWidth - 100) / canvas.width * scale;
    document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
    renderPage(pageNum);
});

// Rotation controls
document.getElementById('rotate-left').addEventListener('click', function() {
    rotation -= 90;
    renderPage(pageNum);
});

document.getElementById('rotate-right').addEventListener('click', function() {
    rotation += 90;
    renderPage(pageNum);
});

// Enhanced summarize function with better UX
document.getElementById('summarize-btn').addEventListener('click', function() {
    const output = document.getElementById('summary-output');
    const button = this;
    
    // Enhanced loading state
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Analyzing...
    `;
    
    output.innerHTML = `
        <div class="slide-in bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <div>
                    <p class="text-blue-800 font-medium">AI is analyzing page ${pageNum}...</p>
                    <p class="text-blue-600 text-sm">This may take a few moments</p>
                </div>
            </div>
        </div>
    `;

    // Your original fetch code
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
                <div class="slide-in space-y-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-green-800 font-medium">Page ${pageNum} Summary</h3>
                        </div>
                        <div class="text-gray-700 leading-relaxed">${data.output}</div>
                    </div>
                </div>
            `;
            
            // Add to history
            addToHistory(pageNum, data.output);
        } else {
            output.innerHTML = `
                <div class="slide-in bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-800 font-medium">Failed to generate summary</p>
                    </div>
                    <p class="text-red-600 text-sm mt-1">Please try again later</p>
                </div>
            `;
        }
    })
    .catch(err => {
        output.innerHTML = `
            <div class="slide-in bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-red-800 font-medium">Connection Error</p>
                        <p class="text-red-600 text-sm">${err.message}</p>
                    </div>
                </div>
            </div>
        `;
    })
    .finally(() => {
        // Reset button
        button.disabled = false;
        button.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span>Summarize Current Page</span>
        `;
    });
});

// Summary history functionality
function addToHistory(page, summary) {
    const historySection = document.getElementById('summary-history');
    const historyList = document.getElementById('history-list');
    
    const historyItem = document.createElement('div');
    historyItem.className = 'bg-gray-50 border border-gray-200 rounded-lg p-3 cursor-pointer hover:bg-gray-100 transition-colors';
    historyItem.innerHTML = `
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Page ${page}</span>
            <span class="text-xs text-gray-500">${new Date().toLocaleTimeString()}</span>
        </div>
        <p class="text-sm text-gray-600 line-clamp-2">${summary.substring(0, 100)}...</p>
    `;
    
    historyItem.addEventListener('click', () => {
        document.getElementById('summary-output').innerHTML = `
            <div class="space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-blue-800 font-medium">Page ${page} Summary</h3>
                    </div>
                    <div class="text-gray-700 leading-relaxed">${summary}</div>
                </div>
            </div>
        `;
    });
    
    historyList.prepend(historyItem);
    historySection.classList.remove('hidden');
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.target.tagName.toLowerCase() === 'input') return;
    
    switch(e.key) {
        case 'ArrowLeft':
        case 'ArrowUp':
            e.preventDefault();
            if (pageNum > 1) {
                pageNum--;
                queueRenderPage(pageNum);
            }
            break;
        case 'ArrowRight':
        case 'ArrowDown':
            e.preventDefault();
            if (pageNum < pdfDoc.numPages) {
                pageNum++;
                q