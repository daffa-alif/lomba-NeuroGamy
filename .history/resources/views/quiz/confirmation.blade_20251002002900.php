<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Confirmation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md text-center">
        <h1 class="text-3xl font-bold text-gray-800">Mulai Kuis</h1>
        <p class="text-gray-600">
            Anda akan memulai kuis yang terdiri dari 10 pertanyaan pilihan ganda.
            Pastikan Anda siap sebelum memulai. Semoga berhasil!
        </p>
        <a href="{{ route('quiz.index') }}" class="inline-block w-full px-5 py-3 text-lg font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-300">
            Mulai Kuis Sekarang
        </a>
        <a href="{{ route('quiz.results') }}" class="inline-block w-full px-5 py-3 mt-4 text-lg font-medium text-blue-600 bg-transparent border border-blue-600 rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-300">
            Lihat Riwayat Skor
        </a>
    </div>
</body>
</html>
