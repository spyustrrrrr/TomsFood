@extends('layouts.app')

@section('title', 'Register - Toman Food')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Register</h2>

        <form action="{{ route('register') }}" method="POST">
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

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('email') border-red-500 @enderror"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- First Name -->
            <div class="mb-4">
                <label for="first_name" class="block text-gray-700 font-semibold mb-2">First Name</label>
                <input 
                    type="text" 
                    name="first_name" 
                    id="first_name" 
                    value="{{ old('first_name') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('first_name') border-red-500 @enderror"
                    required
                >
                @error('first_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label for="last_name" class="block text-gray-700 font-semibold mb-2">Last Name</label>
                <input 
                    type="text" 
                    name="last_name" 
                    id="last_name" 
                    value="{{ old('last_name') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('last_name') border-red-500 @enderror"
                    required
                >
                @error('last_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
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
                <p class="text-gray-500 text-xs mt-1">Minimum 6 characters</p>
            </div>

            <!-- Password Confirmation -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    required
                >
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 font-semibold transition"
            >
                Register
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-orange-600 hover:underline font-semibold">
                Login here
            </a>
        </p>
    </div>
</div>
@endsection