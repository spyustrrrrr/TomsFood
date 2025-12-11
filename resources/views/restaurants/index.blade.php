{{-- 
    File: resources/views/restaurants/index.blade.php
    FIXED: Status hanya informasi, tidak block reservasi
--}}

@extends('layouts.app')

@section('title', 'Daftar Restoran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">🏪 Daftar Restoran</h2>

    @if($restaurants->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($restaurants as $restaurant)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    {{-- Restaurant Image --}}
                    @if($restaurant->image)
                        <img src="{{ $restaurant->image }}" 
                             alt="{{ $restaurant->name }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                            <span class="text-6xl">🍽️</span>
                        </div>
                    @endif

                    {{-- Restaurant Info --}}
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $restaurant->name }}</h3>
                        
                        {{-- Rating --}}
                        <div class="flex items-center mb-3">
                            <span class="text-yellow-500">
                                @for($i = 0; $i < floor($restaurant->rating ?? 0); $i++)
                                    ⭐
                                @endfor
                            </span>
                            <span class="text-gray-600 text-sm ml-2">
                                ({{ number_format($restaurant->rating ?? 0, 1) }})
                            </span>
                        </div>

                        {{-- Description --}}
                        @if($restaurant->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $restaurant->description }}
                            </p>
                        @endif

                        {{-- Operating Hours --}}
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <span class="mr-2">🕒</span>
                            <span>
                                {{ $restaurant->formatted_opening_hours ?? '08:00' }} - 
                                {{ $restaurant->formatted_closing_hours ?? '22:00' }}
                            </span>
                        </div>

                        {{-- Status & Capacity Badges - HANYA INFORMASI --}}
                        <div class="flex gap-2 mb-4">
                            {{-- Status hanya informasi waktu sekarang --}}
                            @php
                                $isCurrentlyOpen = method_exists($restaurant, 'isOpen') ? $restaurant->isOpen() : false;
                            @endphp
                            
                            @if($isCurrentlyOpen)
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                    ✓ Buka Sekarang
                                </span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">
                                    💤 Tutup Sekarang
                                </span>
                            @endif
                            
                            @if($restaurant->table_capacity)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">
                                    👥 {{ $restaurant->table_capacity }}
                                </span>
                            @endif
                        </div>

                        {{-- Info: Tetap Bisa Reservasi --}}
                        @if(!$isCurrentlyOpen)
                            <p class="text-xs text-gray-500 mb-3 italic">
                                💡 Bisa reservasi untuk jam {{ $restaurant->formatted_opening_hours ?? '08:00' }} - {{ $restaurant->formatted_closing_hours ?? '22:00' }}
                            </p>
                        @endif

                        {{-- Action Buttons - ALWAYS ENABLED --}}
                        <div class="space-y-2">
                            <a href="{{ route('restaurants.show', $restaurant->id) }}" 
                               class="block w-full text-center px-4 py-2 border-2 border-orange-600 text-orange-600 rounded-lg font-medium hover:bg-orange-50 transition">
                                👁️ Lihat Detail & Menu
                            </a>
                            
                            @auth
                                <a href="{{ route('reservations.create', ['restaurant_id' => $restaurant->id]) }}" 
                                   class="block w-full text-center px-4 py-2 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition">
                                    📅 Reservasi Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="block w-full text-center px-4 py-2 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition">
                                    🔐 Login untuk Reservasi
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-12 text-center">
            <div class="text-6xl mb-4">🏪</div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Restoran</h3>
            <p class="text-gray-600 mb-6">
                Restoran akan segera hadir. Pantau terus untuk update terbaru!
            </p>
            <a href="{{ route('home') }}" 
               class="inline-block px-6 py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition">
                ← Kembali ke Home
            </a>
        </div>
    @endif
</div>
@endsection