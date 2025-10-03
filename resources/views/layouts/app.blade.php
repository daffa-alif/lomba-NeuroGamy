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

    <!-- Google Fonts: Inter -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white">
        <div class="container mx-auto max-w-6xl px-6 py-6 flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="text-2xl font-bold tracking-tight text-gray-800 hover:text-black transition">
                NeuroSimplicity
            </a>

            <!-- Right Side Menu -->
            <div class="flex items-center space-x-8">
                @auth
                    <!-- Menu for AUTHENTICATED USERS -->
                    <div class="flex items-center space-x-8">
                        <!-- Library Link -->
                        <a href="{{ url('/library') }}" class="text-gray-700 font-semibold hover:text-black transition">Library</a>

                        <!-- Avatar and Username -->
                        <a href="{{ route('profile.index') }}" class="flex items-center space-x-3 group">
                            <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-gray-300 group-hover:border-black transition">
                                @if(Auth::user()->profile_image)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                                        alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" 
                                        alt="Profile" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span class="font-semibold text-gray-800 group-hover:text-black transition">
                                {{ Auth::user()->name }}
                            </span>
                        </a>

                        <!-- Logout Button -->
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-black text-white py-2 px-5 rounded-md hover:bg-gray-800 transition font-semibold">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Menu for GUESTS -->
                    <div class="flex items-center space-x-8">
                         <a href="{{ url('/library') }}" class="text-gray-700 font-semibold hover:text-black transition">Library</a>
                        <a href="{{ url('/login') }}" class="text-gray-700 font-semibold hover:text-black transition">Login</a>
                        <a href="{{ url('/register') }}" class="bg-black text-white py-2 px-5 rounded-md hover:bg-gray-800 transition font-semibold">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content (Konten dinamis akan dimuat di sini) -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-gray-200 py-6 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} 
        <span class="font-semibold text-gray-800">{{ config('app.name', 'Neurogamy') }}</span>. 
        All rights reserved.
    </footer>

</body>
</html>

