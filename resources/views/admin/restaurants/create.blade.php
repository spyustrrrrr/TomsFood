@extends('layouts.admin')

@section('title', 'Add Restaurant')
@section('page-title', 'Add New Restaurant')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Restaurant Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('description') border-red-500 @enderror"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-semibold mb-2">Restaurant Image</label>
                <input 
                    type="file" 
                    name="image" 
                    id="image" 
                    accept="image/*"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('image') border-red-500 @enderror"
                >
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Max 2MB (JPG, PNG, WEBP)</p>
            </div>

            <div class="mb-6">
                <label for="rating" class="block text-gray-700 font-semibold mb-2">Rating (0-5)</label>
                <input 
                    type="number" 
                    name="rating" 
                    id="rating" 
                    value="{{ old('rating', 0) }}"
                    step="0.1"
                    min="0"
                    max="5"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                >
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700">
                    Create Restaurant
                </button>
                <a href="{{ route('admin.restaurants.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection