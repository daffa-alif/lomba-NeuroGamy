<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel Chatbot') }}</title>

    <!-- TailwindCSS via CDN -->
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
            <div class="flex items-center space-x-4">
                <a href="{{ url('/chatbot') }}" class="text-gray-700 hover:text-blue-600 px-3">Chatbot</a>

                <!-- Authentication Card -->
                @auth
                    <div class="flex items-center bg-gray-100 rounded-lg px-3 py-2 shadow-sm">
                        <!-- Empty profile circle -->
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600">
                            <span class="text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>

                        <!-- User name -->
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ Auth::user()->name }}
                            </p>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-red-500 hover:underline">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ url('/login') }}" class="text-gray-700 hover:text-blue-600 px-3">Login</a>
                    <a href="{{ url('/register') }}" class="text-gray-700 hover:text-blue-600 px-3">Register</a>
                @endauth
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
    