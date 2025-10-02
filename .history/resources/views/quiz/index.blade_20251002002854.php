<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quiz Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .option-label:has(input:checked) {
            background-color: #3B82F6; /* bg-blue-600 */
            color: white;
            border-color: #2563EB; /* border-blue-700 */
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6 md:p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $quiz['title'] }}</h1>
                    <p class="text-gray-600 mt-2">{{ $quiz['description'] }}</p>
                </div>
                <a href="{{ route('quiz.regenerate') }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Buat Kuis Baru
                </a>
            </div>

            <form id="quiz-form">
                @csrf
                {{-- NOTE: Pass the actual book ID from the page that links to this quiz --}}
                {{-- For demonstration, a placeholder value is used. --}}
                <input type="hidden" name="books_id" value="1"> 
                <input type="hidden" name="quiz_data" value="{{ json_encode($quiz) }}">

                <div class="space-y-8">
                    @foreach($quiz['questions'] as $index => $question)
                        <div class="question-block" id="question-{{ $question['id'] }}">
                            <p class="text-lg font-semibold text-gray-800 mb-4">
                                {{ $index + 1 }}. {{ $question['question'] }}
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($question['options'] as $key => $option)
                                    <label class="option-label border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-200 transition duration-200">
                                        <input type="radio" name="answers[{{ $question['id'] }}]" value="{{ $key }}" class="hidden">
                                        <span class="font-bold mr-3">{{ $key }}.</span>
                                        <span>{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 text-center">
                    <button type="submit" class="w-full sm:w-auto px-12 py-3 text-lg font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-300">
                        Selesai & Kirim Jawaban
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Result Modal -->
    <div id="result-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-sm w-full text-center">
            <h2 class="text-2xl font-bold mb-4" id="modal-title">Hasil Kuis Anda</h2>
            <div id="modal-body" class="mb-6 text-lg">
                <p>Memuat hasil...</p>
            </div>
            <div class="flex justify-center space-x-4">
                <button onclick="document.getElementById('result-modal').classList.add('hidden')" class="px-6 py-2 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400">Tutup</button>
                <a href="{{ route('quiz.results') }}" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Lihat Riwayat</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('quiz-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const modal = document.getElementById('result-modal');
            const modalBody = document.getElementById('modal-body');
            modal.classList.remove('hidden');
            modalBody.innerHTML = '<p>Menghitung skor...</p>';

            const formData = new FormData(this);
            const plainFormData = Object.fromEntries(formData.entries());
            // Need to structure answers correctly for validation
            const structuredData = {
                _token: plainFormData._token,
                books_id: plainFormData.books_id,
                quiz_data: plainFormData.quiz_data,
                answers: {}
            };
            for (const key in plainFormData) {
                if (key.startsWith('answers[')) {
                    const id = key.match(/\[(.*?)\]/)[1];
                    structuredData.answers[id] = plainFormData[key];
                }
            }

            try {
                const response = await fetch("{{ route('quiz.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(structuredData)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    modalBody.innerHTML = `
                        <p class="text-4xl font-bold text-green-600">${result.score} / ${result.total}</p>
                        <p class="text-gray-700 mt-2">${result.message}</p>
                    `;
                } else {
                    modalBody.innerHTML = `<p class="text-red-500">${result.message || 'Gagal mengirim jawaban. Silakan coba lagi.'}</p>`;
                }

            } catch (error) {
                console.error('Submission error:', error);
                modalBody.innerHTML = '<p class="text-red-500">Terjadi kesalahan. Periksa koneksi Anda dan coba lagi.</p>';
            }
        });
    </script>
</body>
</html>
