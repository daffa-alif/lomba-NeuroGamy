@extends('layouts.app')

@section('title', 'Digital Library')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Digital Library</h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">
            Discover our extensive collection of digital books across various categories. 
            Find, read, and download books that inspire and educate.
        </p>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('library.index') }}" class="space-y-4 md:space-y-0 md:flex md:gap-4 md:items-end">
            <!-- Search Input -->
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Search Books
                </label>
                <div class="relative">
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ $search ?? '' }}"
                        placeholder="Search by title or description..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Category
                </label>
                <select 
                    id="category" 
                    name="category" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                    class="w-full md:w-auto px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
            </div>

            <!-- Clear Filters -->
            @if($search || ($selectedCategory && $selectedCategory !== 'all'))
                <div>
                    <a 
                        href="{{ route('library.index') }}" 
                        class="w-full md:w-auto inline-block px-4 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors text-center"
                    >
                        Clear Filters
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Results Summary -->
    <div class="flex justify-between items-center mb-6">
        <div class="text-gray-600">
            @if($search || ($selectedCategory && $selectedCategory !== 'all'))
                <p>
                    Showing {{ $books->count() }} of {{ $books->total() }} books
                    @if($search)
                        for "<span class="font-medium">{{ $search }}</span>"
                    @endif
                    @if($selectedCategory && $selectedCategory !== 'all')
                        in <span class="font-medium">{{ $classifications->find($selectedCategory)->classification ?? 'Selected Category' }}</span>
                    @endif
                </p>
            @else
                <p>Showing {{ $books->count() }} of {{ $books->total() }} books</p>
            @endif
        </div>
        
        <!-- View Toggle (Optional) -->
        <div class="hidden md:flex gap-2">
            <button id="gridView" class="p-2 bg-blue-600 text-white rounded-lg">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
            </button>
            <button id="listView" class="p-2 bg-gray-300 text-gray-700 rounded-lg">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Books Grid -->
    @if($books->count() > 0)
        <div id="booksContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($books as $book)
                <div class="book-card bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                    <!-- Book Cover/Icon -->
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                            </svg>
                            <p class="text-sm font-medium">{{ strtoupper(pathinfo($book->file_name, PATHINFO_EXTENSION)) }}</p>
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="p-4">
                        <div class="mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ $book->classification->classification ?? 'Uncategorized' }}
                            </span>
                        </div>
                        
                        <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">
                            {{ $book->book_title }}
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ $book->book_description ?? 'No description available.' }}
                        </p>

                        <!-- Action Buttons -->
<!-- Action Buttons -->
<div class="flex gap-2">
    <a 
        href="{{ route('library.show', $book->id) }}" 
        class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
    >
        View Details
    </a>
    <a 
        href="{{ route('library.download', $book->id) }}" 
        class="bg-green-600 text-white py-2 px-3 rounded-lg hover:bg-green-700 transition-colors"
        title="Download {{ $book->book_title }}"
    >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </a>
    <a 
        href="{{ route('reading.index', $book->id) }}" 
        class="bg-purple-600 text-white py-2 px-3 rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium"
        title="Read {{ $book->book_title }}"
    >
        Read This Book
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
        <div class="text-center py-12">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
            <h3 class="text-xl font-medium text-gray-800 mb-2">No books found</h3>
            <p class="text-gray-600 mb-4">
                @if($search || ($selectedCategory && $selectedCategory !== 'all'))
                    Try adjusting your search criteria or browse all books.
                @else
                    No books are currently available in the library.
                @endif
            </p>
            @if($search || ($selectedCategory && $selectedCategory !== 'all'))
                <a 
                    href="{{ route('library.index') }}" 
                    class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Browse All Books
                </a>
            @endif
        </div>
    @endif
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
        transform: translateY(-2px);
    }
    
    .book-card {
        transition: all 0.3s ease;
    }
    
    /* List view styles (optional feature) */
    .list-view .book-card {
        display: flex;
        flex-direction: row;
    }
    
    .list-view .book-card > div:first-child {
        flex-shrink: 0;
        width: 120px;
        height: 120px;
    }
    
    .list-view .book-card > div:last-child {
        flex: 1;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality (optional)
    const gridViewBtn = document.getElementById('gridView');
    const listViewBtn = document.getElementById('listView');
    const container = document.getElementById('booksContainer');
    
    if (gridViewBtn && listViewBtn && container) {
        gridViewBtn.addEventListener('click', function() {
            container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8';
            gridViewBtn.className = 'p-2 bg-blue-600 text-white rounded-lg';
            listViewBtn.className = 'p-2 bg-gray-300 text-gray-700 rounded-lg';
        });
        
        listViewBtn.addEventListener('click', function() {
            container.className = 'list-view space-y-4 mb-8';
            listViewBtn.className = 'p-2 bg-blue-600 text-white rounded-lg';
            gridViewBtn.className = 'p-2 bg-gray-300 text-gray-700 rounded-lg';
        });
    }
    
    // Auto-submit form on category change (optional)
    const categorySelect = document.getElementById('category');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            // Uncomment the line below if you want auto-submit on category change
            // this.form.submit();
        });
    }
});
</script>
@endpush
@endsection