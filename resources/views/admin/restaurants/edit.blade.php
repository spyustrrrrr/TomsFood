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
                <label for="address" class="block text-gray-700 font-semibold mb-2">Address</label>
                <textarea 
                    name="address" 
                    id="address" 
                    rows="2"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('address') border-red-500 @enderror"
                    placeholder="Full restaurant address"
                >{{ old('address', $restaurant->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="phone" class="block text-gray-700 font-semibold mb-2">Phone</label>
                    <input 
                        type="text" 
                        name="phone" 
                        id="phone" 
                        value="{{ old('phone', $restaurant->phone) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('phone') border-red-500 @enderror"
                        placeholder="e.g. 0812-3456-7890"
                    >
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="table_capacity" class="block text-gray-700 font-semibold mb-2">Table Capacity</label>
                    <input 
                        type="number" 
                        name="table_capacity" 
                        id="table_capacity" 
                        value="{{ old('table_capacity', $restaurant->table_capacity ?? 20) }}"
                        min="1"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('table_capacity') border-red-500 @enderror"
                    >
                    @error('table_capacity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="opening_hours" class="block text-gray-700 font-semibold mb-2">Opening Hours</label>
                    <input 
                        type="time" 
                        name="opening_hours" 
                        id="opening_hours" 
                        value="{{ old('opening_hours', $restaurant->opening_hours ? \Carbon\Carbon::parse($restaurant->opening_hours)->format('H:i') : '08:00') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('opening_hours') border-red-500 @enderror"
                    >
                    @error('opening_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="closing_hours" class="block text-gray-700 font-semibold mb-2">Closing Hours</label>
                    <input 
                        type="time" 
                        name="closing_hours" 
                        id="closing_hours" 
                        value="{{ old('closing_hours', $restaurant->closing_hours ? \Carbon\Carbon::parse($restaurant->closing_hours)->format('H:i') : '22:00') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('closing_hours') border-red-500 @enderror"
                    >
                    @error('closing_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="booking_advance_hours" class="block text-gray-700 font-semibold mb-2">Min. Booking (Hours)</label>
                    <input 
                        type="number" 
                        name="booking_advance_hours" 
                        id="booking_advance_hours" 
                        value="{{ old('booking_advance_hours', $restaurant->booking_advance_hours ?? 2) }}"
                        min="1"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('booking_advance_hours') border-red-500 @enderror"
                    >
                    @error('booking_advance_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
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