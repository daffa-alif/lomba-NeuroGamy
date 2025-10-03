@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-16 px-6">
    <div class="bg-white p-12 rounded-xl shadow-2xl w-full max-w-lg">
        <h2 class="text-3xl font-bold mb-6 text-left">Register</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="block mb-2 font-bold text-gray-800">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="w-full p-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition" required>
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block mb-2 font-bold text-gray-800">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full p-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition" required>
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block mb-2 font-bold text-gray-800">Password</label>
                <input type="password" id="password" name="password"
                       class="w-full p-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition" required>
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="password_confirmation" class="block mb-2 font-bold text-gray-800">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full p-3 border border-gray-400 rounded-md focus:ring-2 focus:ring-black focus:border-black transition" required>
            </div>

            <div class="pt-4 flex items-center justify-between">
                <button type="submit" class="bg-black text-white py-2 px-8 rounded-md hover:bg-gray-800 transition font-semibold">
                    Register
                </button>
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-black hover:underline">Login</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection

