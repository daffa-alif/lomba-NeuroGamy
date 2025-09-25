@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="w-full border rounded px-3 py-2" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Login
        </button>
    </form>

    <p class="mt-4 text-sm">
        Don’t have an account? 
        <a href="{{ route('register') }}" class="text-blue-600">Register</a>
    </p>
</div>
@endsection
