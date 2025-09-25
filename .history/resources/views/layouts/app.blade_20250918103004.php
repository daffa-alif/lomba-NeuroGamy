<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel Chatbot') }}</title>

    <!-- TailwindCSS via CDN (optional, remove if using Vite/Tailwind) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSRF Token for JS fetch -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="antialiased bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600">
                {{ config('app.name', 'Laravel Chatbot') }}
            </a>
            <div>
                <a href="{{ url('/chatbot') }}" class="text-gray-700 hover:text-blue-600 px-3">Chatbot</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-12 py-6 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel Chatbot') }}. All rights reserved.
    </footer>

</body>
</html>
