@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    <!-- PDF Viewer -->
    <div class="w-2/3 border-r">
        <iframe src="{{ asset('storage/books/' . $book->file_name) }}" class="w-full h-full"></iframe>
    </div>

    <!-- Ringkasan AI -->
    <div class="w-1/3 p-4 overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Ringkasan AI</h2>

        <button id="summarizeBtn" 
                class="px-4 py-2 bg-blue-600 text-white rounded">
            Generate Summary
        </button>

        <div id="summary
