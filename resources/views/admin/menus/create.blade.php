@extends('layouts.admin')

@section('title', 'Add Menu')
@section('page-title', 'Add New Menu')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.menus.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="restaurant_id" class="block text-gray-700 font-semibold mb-2">Restaurant</label>
                <select 
                    name="restaurant_id" 
                    id="restaurant_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('restaurant_id') border-red-500 @enderror"
                    required
                >
                    <option value="">-- Select Restaurant --</option>
                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}" {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                            {{ $restaurant->name }}
                        </option>
                    @endforeach
                </select>
                @error('restaurant_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Menu Name</label>
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
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                >{{ old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <label for="price" class="block text-gray-700 font-semibold mb-2">Price (Rp)</label>
                <input 
                    type="number" 
                    name="price" 
                    id="price" 
                    value="{{ old('price') }}"
                    min="0"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('price') border-red-500 @enderror"
                    required
                >
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700">
                    Create Menu
                </button>
                <a href="{{ route('admin.menus.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection