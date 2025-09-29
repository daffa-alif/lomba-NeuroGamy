@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row h-screen bg-gray-100">
    <!-- PDF Preview -->
    <div class="w-full md:w-3/4 flex flex-col border-b md:border-b-0 md:border-r bg-gray-50">
        <!-- PDF Container -->
        <div id="pdf-container" class="flex-1 overflow-auto flex justify-center items-start p-4">
            <canvas id="pdf-canvas" class="bg-white shadow rounded-md"></canvas>
        </div>

        <!-- Navigasi Halaman -->
        <div class="p-3 bg-gray-200 flex justify-center items-center gap-4 border-t">
            <button id="prev-page" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow transition text-sm">
                ⬅ Prev
            </button>
            <span class="font-medium text-gray-700 text-sm">
                Halaman <span id="page-num">1</span> / <span id="page-count">?</span>
            </span>
            <button id="next-page" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow transition text-sm">
                Next ➡
            </button>

            <!-- Tombol Submit -->
            <button id="submit-reading" 
                    class="hidden bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded shadow transition text-sm ml-4">
                ✅ Submit
            </button>
        </div>
    </div>

    <!-- Ringkasan AI -->
    <div class="w-full md:w-1/4 p-4 overflow-y-auto bg-gray-50">
        <div class="bg-white rounded-lg shadow p-4 h-full flex flex-col max-h-[90vh]">
            <h2 class="text-lg font-semibold mb-3 text-gray-800">📖 Ringkasan AI</h2>

            <div id="summary-output" class="text-gray-700 flex-1 overflow-y-auto text-sm leading-relaxed pr-1">
                <p class="text-gray-500">Belum ada ringkasan.</p>
            </div>

            <button id="summarize-btn" 
                    class="mt-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-3 py-2 rounded-md shadow hover:from-blue-700 hover:to-blue-800 transition text-sm font-medium">
                🔍 Ringkas Halaman Ini
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
    scale = 1.1,
    canvas = document.getElementById("pdf-canvas"),
    ctx = canvas.getContext("2d");

const url = "{{ asset('storage/' . $book->file_name) }}";

const maxPages = {{ $maxPages }};
const totalPages = {{ $totalPages }};
const submitBtn = document.getElementById('submit-reading');

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

            // panggil afterRenderPage -> cek tombol submit
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

// Navigasi
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

// Ringkas
document.getElementById('summarize-btn').addEventListener('click', function() {
    const output = document.getElementById('summary-output');
    output.innerHTML = "<p class='text-blue-500 italic'>⏳ Meminta ringkasan ke AI...</p>";

    fetch("{{ route('reading.summarize', $book->id) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        body: JSON.stringify({ page: pageNum })
    })
    .then(res => res.json())
    .then(data => {
        if (data.output) {
            output.innerHTML = "<p>" + data.output.replace(/\n/g, "<br>") + "</p>";
        } else {
            output.innerHTML = "<p class='text-red-500'>⚠️ Gagal menghasilkan ringkasan.</p>";
        }
    })
    .catch(err => {
        output.innerHTML = "<p class='text-red-500'>❌ Error: " + err.message + "</p>";
    });
});

// Submit Reading
submitBtn.addEventListener('click', function() {
    submitBtn.disabled = true;
    submitBtn.innerText = "⏳ Mengirim...";

    fetch("{{ route('scorelogs.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            books_id: {{ $book->id }},
            title: @json($book->title)
        })
    })
    .then(async res => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) throw { status: res.status, data };
        return data;
    })
    .then(data => {
        alert("✅ " + (data.message || "Log berhasil dibuat."));
        submitBtn.innerText = "✔ Submitted";
    })
    .catch(err => {
        if (err.status === 409) {
            alert("⚠️ " + (err.data.message || "Log sudah ada."));
        } else {
            alert("❌ Gagal menyimpan log. Cek console.");
        }
        submitBtn.disabled = false;
        submitBtn.innerText = "✅ Submit";
    });
});
</script>
@endsection
