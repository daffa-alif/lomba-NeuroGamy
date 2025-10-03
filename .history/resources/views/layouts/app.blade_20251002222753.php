<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Neurogamy') }}</title>

    <!-- TailwindCSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSRF Token for JS fetch -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-sans">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600 hover:text-blue-700 transition">
                {{ config('app.name', 'Laravel Chatbot') }}
            </a>

            <!-- Menu -->
            <div class="flex items-center space-x-6">
                <a href="{{ url('/library') }}" class="text-gray-700 hover:text-blue-600 transition">Library</a>

                @auth
                <!-- Profile -->
                <div class="flex items-center space-x-3">
                    <!-- Avatar -->
                    <a href="{{ route('profile.index') }}" class="w-9 h-9 rounded-full overflow-hidden border border-gray-300">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                                 alt="Profile" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" 
                                 alt="Profile" class="w-full h-full object-cover">
                        @endif
                    </a>

                    <!-- Username -->
                    <a href="{{ route('profile.index') }}" 
                       class="text-sm font-medium text-gray-800 hover:text-blue-600 transition">
                        {{ Auth::user()->name }}
                    </a>

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" 
                                class="px-3 py-1 text-xs text-red-600 border border-red-300 rounded hover:bg-red-50 transition">
                            Logout
                        </button>
                    </form>
                </div>
                @else
                    <a href="{{ url('/login') }}" class="text-gray-700 hover:text-blue-600 transition">Login</a>
                    <a href="{{ url('/register') }}" class="text-gray-700 hover:text-blue-600 transition">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-12 border-t border-gray-200 py-6 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} 
        <span class="font-semibold text-blue-600">{{ config('app.name', 'Laravel Chatbot') }}</span>. 
        All rights reserved.
    </footer>

</body>
</html>
