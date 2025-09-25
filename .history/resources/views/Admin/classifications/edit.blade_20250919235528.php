@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Edit Classification</h2>

    <form action="{{ route('classifications.update', $classification) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <input type="text" name="classification" value="{{ old('classification', $classification->classification) }}"
               class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-300">

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Update
        </button>
        <a href="{{ route('classifications.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
