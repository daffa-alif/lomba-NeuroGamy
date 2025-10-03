@extends('layouts.app')

@section('title', 'Digital Library')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Digital Library</h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">
            Discover our extensive collection of digital books across various categories. 
            Find, read, and download books that inspire and educate.
        </p>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-2xl p-8 mb-12 max-w-4xl mx-auto">
        <form method="GET" action="{{ route('library.index') }}" class="space-y-4 md:space-y-0 md:flex md:gap-4 md:items-end">
            <!-- Search Input -->
            <div class="flex-1">
                <label for="search" class="block mb-2 font-bold text-gray-800">
                    Search Books
                </label>
                <div class="relative">
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ $search ?? '' }}"
                        placeholder="Search by title..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="md:w-64">
                <label for="category" class="block mb-2 font-bold text-gray-800">
                    Category
                </label>
                <select 
                    id="category" 
                    name="category" 
                    class="w-full px-3 py-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition"
                >
                    <option value="all" {{ ($selectedCategory ?? 'all') == 'all' ? 'selected' : '' }}>
                        All Categories
                    </option>
                    @foreach($classifications as $classification)
                        <option 
                            value="{{ $classification->id }}" 
                            {{ ($selectedCategory ?? '') == $classification->id ? 'selected' : '' }}
                        >
                            {{ $classification->classification }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Button -->
            <div>
                <button 
                    type="submit" 
                    class="w-full md:w-auto px-6 py-3 bg-black text-white font-semibold rounded-md hover:bg-gray-800 focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors"
                >
                    Search
                </button>
            </div>

            <!-- Clear Filters -->
            @if($search || ($selectedCategory && $selectedCategory !== 'all'))
                <div>
                    <a 
                        href="{{ route('library.index') }}" 
                        class="w-full md:w-auto inline-block px-6 py-3 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700 transition-colors text-center"
                    >
                        Clear
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Results Summary -->
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div class="text-gray-600">
                @if($search || ($selectedCategory && $selectedCategory !== 'all'))
                    <p>
                        Showing {{ $books->count() }} of {{ $books->total() }} books
                        @if($search)
                            for "<span class="font-medium text-black">{{ $search }}</span>"
                        @endif
                        @if($selectedCategory && $selectedCategory !== 'all')
                            in <span class="font-medium text-black">{{ $classifications->find($selectedCategory)->classification ?? 'Selected Category' }}</span>
                        @endif
                    </p>
                @else
                    <p>Showing {{ $books->count() }} of {{ $books->total() }} books</p>
                @endif
            </div>
        </div>

        <!-- Books Grid -->
        @if($books->count() > 0)
            <div id="booksContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-8">
                @foreach($books as $book)
                    <div class="book-card bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                        <!-- Book Visual -->
                        <div class="h-48 bg-gray-50 flex items-center justify-center p-6 overflow-hidden rounded-t-xl">
                            <!-- Abstract Ink SVG -->
                            <svg class="w-24 h-24 text-black opacity-80" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 20C44.77 20 0 64.77 0 120c0 47.38 35.04 86.88 80.62 98.19.11-.47.21-.94.32-1.42 5.3-23.44 2.37-47.53-8.8-69.1-12.8-24.8-14.28-54.8-4.2-82.07C78.43 38.8 89.02 29.53 100 20zm0 0c55.23 0 100 44.77 100 100 0 47.38-35.04 86.88-80.62 98.19-.11-.47-.21-.94-.32-1.42-5.3-23.44-2.37-47.53 8.8-69.1 12.8-24.8 14.28-54.8 4.2-82.07C121.57 38.8 110.98 29.53 100 20z" fill="currentColor"/>
                            </svg>
                        </div>

                        <!-- Book Info -->
                        <div class="p-6 flex-grow flex flex-col">
                            <span class="inline-block self-start px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-md mb-3">
                                {{ $book->classification->classification ?? 'Uncategorized' }}
                            </span>
                            
                            <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 flex-grow">
                                {{ $book->book_title }}
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-6 line-clamp-3">
                                {{ $book->book_description ?? 'No description available.' }}
                            </p>
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-3 mt-auto">
                                <a 
                                    href="{{ route('reading.index', $book->id) }}" 
                                    class="flex-1 text-center bg-black text-white py-2 px-4 rounded-md hover:bg-gray-800 transition-colors text-sm font-semibold"
                                    title="Read {{ $book->book_title }}">
                                    Read This Book
                                </a>
                                <a 
                                    href="{{ route('library.download', $book->id) }}" 
                                    class="p-2 border-2 border-gray-300 text-gray-600 rounded-md hover:bg-gray-100 hover:border-gray-400 transition-colors"
                                    title="Download {{ $book->book_title }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $books->links() }}
            </div>
        @else
            <!-- No Books Found -->
            <div class="text-center py-16 bg-white rounded-xl shadow-lg border">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No books found</h3>
                <p class="text-gray-600 mb-6">
                    @if($search || ($selectedCategory && $selectedCategory !== 'all'))
                        Try adjusting your search criteria.
                    @else
                        No books are currently available in the library.
                    @endif
                </p>
                @if($search || ($selectedCategory && $selectedCategory !== 'all'))
                    <a 
                        href="{{ route('library.index') }}" 
                        class="inline-block bg-black text-white px-6 py-2 rounded-md hover:bg-gray-800 transition-colors font-semibold"
                    >
                        Clear Search
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .book-card:hover {
        transform: translateY(-5px);
    }
    
</style>
@endpush
@endsection  