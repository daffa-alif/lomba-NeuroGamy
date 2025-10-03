<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1f2937;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <aside id="sidebar" class="fixed left-0 top-0 z-40 w-64 h-screen bg-black text-white shadow-2xl sidebar-transition custom-scrollbar overflow-y-auto flex flex-col">
        
        <div class="flex flex-col p-8 pb-4">
            <h2 class="text-white text-3xl font-bold tracking-widest">ADMIN</h2>
            <h2 class="text-white text-3xl font-bold tracking-widest -mt-2">PANEL</h2>
        </div>
    
        <div class="flex-grow flex flex-col justify-center">
            <nav class="px-8 space-y-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex flex-col items-start {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                    <span class="font-semibold text-xl">Dashboard</span>
                    <p class="text-xs text-gray-500 mt-1">
                        Overview and analytics
                    </p>
                </a>
    
                <a href="{{ route('users.index') }}" 
                   class="flex flex-col items-start {{ request()->routeIs('users.index') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                    <span class="font-semibold text-xl">Users</span>
                    <p class="text-xs text-gray-500 mt-1">
                        Manage user accounts
                    </p>
                </a>
                
                <a href="{{ route('classifications.index') }}" 
                   class="flex flex-col items-start {{ request()->routeIs('classifications.index') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                    <span class="font-semibold text-xl">Book Categories</span>
                    <p class="text-xs text-gray-500 mt-1">
                        Manage Book
                    </p>
                </a>
    
                <a href="{{ route('books.index') }}" 
                   class="flex flex-col items-start {{ request()->routeIs('books.index') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                    <span class="font-semibold text-xl">Books</span>
                    <p class="text-xs text-gray-500 mt-1">
                        Manage book catalog
                    </p>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center border border-gray-500">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-sm font-medium truncate">Admintlahtahdatang</p>
                </div>
                <div class="relative">
                    <button class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

    <div class="lg:ml-64 min-h-screen flex flex-col bg-white">
        <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li>
                                <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                                <span class="text-gray-900 font-medium">Users</span>
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search" 
                               class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>

                    <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-colors">
                        <i class="fas fa-bell text-lg"></i>
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6">
            <div class="max-w-full">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>

