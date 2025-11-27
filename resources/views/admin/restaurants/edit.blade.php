@extends('layouts.admin')

@section('title', 'Edit Restaurant')
@section('page-title', 'Edit Restaurant')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Restaurant Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $restaurant->name) }}"
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
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                >{{ old('description', $restaurant->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-semibold mb-2">Restaurant Image</label>
                
                @if($restaurant->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-32 h-32 object-cover rounded">
                        <p class="text-sm text-gray-600 mt-1">Current Image</p>
                    </div>
                @endif
                
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
                <p class="text-gray-500 text-xs mt-1">Leave blank to keep current image. Max 2MB (JPG, PNG, WEBP)</p>
            </div>

            <div class="mb-6">
                <label for="rating" class="block text-gray-700 font-semibold mb-2">Rating (0-5)</label>
                <input 
                    type="number" 
                    name="rating" 
                    id="rating" 
                    value="{{ old('rating', $restaurant->rating) }}"
                    step="0.1"
                    min="0"
                    max="5"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                >
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700">
                    Update Restaurant
                </button>
                <a href="{{ route('admin.restaurants.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection