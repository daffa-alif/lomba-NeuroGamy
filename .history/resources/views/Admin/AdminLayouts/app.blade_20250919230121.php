<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    
    <!-- TailwindCSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Custom scrollbar */
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
        
        /* Sidebar transition */
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Active nav item gradient */
        .nav-active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
    </style>
</head>
<body class="bg-slate-50 text-gray-800 font-sans">

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 z-40 w-64 h-screen bg-gradient-to-b from-gray-900 to-gray-800 shadow-2xl sidebar-transition custom-scrollbar overflow-y-auto">
        <!-- Logo Section -->
        <div class="flex items-center justify-between p-6 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-book-open text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-white font-bold text-xl">Admin Panel</h2>
                    <p class="text-gray-400 text-sm">Library Management</p>
                </div>
            </div>
            <button id="sidebarToggle" class="lg:hidden text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="mt-8 px-4">
            <div class="space-y-4">
                <!-- Dashboard -->
                <a href="#" 
                   class="nav-item group flex items-center px-6 py-4 text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-indigo-600 hover:to-blue-600 hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                    <div class="w-8 h-8 mr-4 flex items-center justify-center bg-indigo-500 bg-opacity-20 rounded-xl group-hover:bg-opacity-30 transition-all duration-300">
                        <i class="fas fa-tachometer-alt text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <span class="font-semibold text-base">Dashboard</span>
                        <p class="text-xs text-gray-400 group-hover:text-indigo-100 mt-1">Overview & analytics</p>
                    </div>
                    <div class="ml-auto opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </div>
                </a>

                <!-- Users -->
                <a href="#" 
                   class="nav-item group flex items-center px-6 py-4 text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow-xl nav-active">
                    <div class="w-8 h-8 mr-4 flex items-center justify-center bg-blue-500 bg-opacity-20 rounded-xl group-hover:bg-opacity-30 transition-all duration-300">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <span class="font-semibold text-base">Users</span>
                        <p class="text-xs text-gray-400 group-hover:text-blue-100 mt-1">Manage user accounts</p>
                    </div>
                    <div class="ml-auto opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </div>
                </a>

                <!-- Books -->
                <a href="{{ route" 
                   class="nav-item group flex items-center px-6 py-4 text-gray-300 rounded-2xl hover:bg-gradient-to-r hover:from-emerald-600 hover:to-teal-600 hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                    <div class="w-8 h-8 mr-4 flex items-center justify-center bg-emerald-500 bg-opacity-20 rounded-xl group-hover:bg-opacity-30 transition-all duration-300">
                        <i class="fas fa-book text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <span class="font-semibold text-base">Books</span>
                        <p class="text-xs text-gray-400 group-hover:text-emerald-100 mt-1">Manage book catalog</p>
                    </div>
                    <div class="ml-auto opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </div>
                </a>
            </div>
        </nav>

        <!-- User Profile Section at Bottom -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700 bg-gray-800">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">Admin User</p>
                    <p class="text-gray-400 text-xs">Administrator</p>
                </div>
                <div class="relative">
                    <button class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

    <!-- Main Content Area -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
        <!-- Top Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuButton" class="lg:hidden text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Breadcrumb -->
                    <nav class="hidden md:flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li>
                                <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                                <span class="text-gray-900 font-medium">@yield('breadcrumb', 'Dashboard')</span>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="hidden md:block relative">
                        <input type="text" 
                               placeholder="Search..." 
                               class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>

                    <!-- User Menu -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="hidden md:block text-gray-700 font-medium">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-user-circle mr-3"></i>
                                Profile
                            </a>
                            <a href="/" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-external-link-alt mr-3"></i>
                                View Site
                            </a>
                            <hr class="my-2">
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 transition-colors text-left">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            <div class="max-w-full">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200