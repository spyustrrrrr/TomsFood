@extends('layouts.app')

@section('title', 'Login - Toman Food')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-md p-12 mt-12">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Login</h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    value="{{ old('username') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('username') border-red-500 @enderror"
                    required
                >
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('password') border-red-500 @enderror"
                    required
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 font-semibold transition"
            >
                Login
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-orange-600 hover:underline font-semibold">
                Register here
            </a>
        </p>
    </div>
</div>
@endsection