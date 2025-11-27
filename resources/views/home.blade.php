@extends('layouts.app')

@section('title', 'Home - Toman Food')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to Toman Food</h1>
    <p class="text-gray-600">Order delicious food from your favorite restaurants</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($restaurants as $restaurant)
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        @if($restaurant->image)
            <img src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-full h-48 object-cover">
        @else
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-400">No Image</span>
            </div>
        @endif
        
        <div class="p-6">
            <h3 class="text-xl font-semibold mb-2">{{ $restaurant->name }}</h3>
            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($restaurant->description, 100) }}</p>
            <div class="flex items-center justify-between">
                <span class="text-yellow-500">⭐ {{ $restaurant->rating }}</span>
                <a href="{{ route('restaurants.show', $restaurant->id) }}" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
                    View Menu
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center text-gray-500 py-12">
        No restaurants available yet.
    </div>
    @endforelse
</div>
@endsection