@extends('Admin.AdminLayouts.app')

@section('title', 'Books Management')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Books Management</h1>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">#</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Title</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Author</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Classification</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Created At</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                <tr class="border-t">
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $book->title }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $book->author }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $book->classification->name ?? '-' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $book->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-2 text-sm">
                        <a href="#" class="text-blue-500 hover:underline">Edit</a> |
                        <a href="#" class="text-red-500 hover:underline">Delete</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                        No books available.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
