@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Register</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
            <label class="block">Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded px-3 py-2" required>
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

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

        <div class="mb-4">
            <label class="block">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
            Register
        </button>
    </form>

    <p class="mt-4 text-sm">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-blue-600">Login</a>
    </p>
</div>
@endsection
