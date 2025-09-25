<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    
    <!-- TailwindCSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-gray-800 text-white px-6 py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/admin/dashboard') }}" class="text-lg font-semibold">Admin Panel</a>
            
            <div class="flex items-center space-x-4">
                <a href="{{ url('/') }}" class="hover:text-gray-300">Home</a>
                <a href="{{ url('/logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="hover:text-gray-300">Logout</a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-6">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 mt-10">
        <div class="container mx-auto text-center text-sm">
            &copy; {{ date('Y') }} Admin Panel. All rights reserved.
        </div>
    </footer>

</body>
</html>
