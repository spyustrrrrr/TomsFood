{{-- 
    File: resources/views/restaurants/show.blade.php
    FIXED: Reservasi tetap bisa meskipun restoran tutup (untuk booking di waktu lain)
--}}

@extends('layouts.app')

@section('title', $restaurant->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Restaurant Header --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="md:flex">
            {{-- Image --}}
            <div class="md:w-2/5">
                @if($restaurant->image)
                    <img src="{{ $restaurant->image }}" 
                         alt="{{ $restaurant->name }}"
                         class="w-full h-80 object-cover">
                @else2
                    <div class="w-full h-80 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                        <span class="text-9xl">🍽️</span>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="md:w-3/5 p-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $restaurant->name }}</h1>
                
                {{-- Rating --}}
                <div class="flex items-center mb-4">
                    <span class="text-yellow-500 text-2xl">
                        @for($i = 0; $i < floor($restaurant->rating); $i++)
                            ⭐
                        @endfor
                    </span>
                    <span class="text-gray-600 text-lg ml-2">
                        ({{ number_format($restaurant->rating, 1) }})
                    </span>
                </div>

                {{-- Description --}}
                <p class="text-gray-600 mb-6">{{ $restaurant->description }}</p>

                <hr class="my-6">

                {{-- Details Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    {{-- Operating Hours --}}
                    <div>
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">🕒</span>
                            <div>
                                <p class="font-semibold text-gray-800">Jam Operasional</p>
                                <p class="text-gray-600">
                                    {{ $restaurant->formatted_opening_hours }} - 
                                    {{ $restaurant->formatted_closing_hours }}
                                </p>
                                {{-- Status hanya informasi, tidak block reservasi --}}
                                @if($restaurant->isOpen())
                                    <span class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                        ✓ Sedang Buka Sekarang
                                    </span>
                                @else
                                    <span class="inline-block mt-1 px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">
                                        💤 Sedang Tutup (Bisa Reservasi untuk Nanti)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Capacity --}}
                    <div>
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">👥</span>
                            <div>
                                <p class="font-semibold text-gray-800">Kapasitas</p>
                                <p class="text-gray-600">{{ $restaurant->table_capacity }} orang</p>
                            </div>
                        </div>
                    </div>

                    {{-- Phone --}}
                    @if($restaurant->phone)
                        <div>
                            <div class="flex items-start">
                                <span class="text-2xl mr-3">📞</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Telepon</p>
                                    <a href="tel:{{ $restaurant->phone }}" class="text-orange-600 hover:text-orange-700">
                                        {{ $restaurant->phone }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Address --}}
                    @if($restaurant->address)
                        <div class="md:col-span-2">
                            <div class="flex items-start">
                                <span class="text-2xl mr-3">📍</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Alamat</p>
                                    <p class="text-gray-600">{{ $restaurant->address }}</p>
                                    @if($restaurant->map_link)
                                        <a href="{{ $restaurant->map_link }}" 
                                           target="_blank"
                                           class="inline-block mt-2 text-orange-600 hover:text-orange-700 text-sm font-medium">
                                            🗺️ Lihat di Google Maps →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <hr class="my-6">

                {{-- CTA Button - ALWAYS SHOW (tidak tergantung status buka/tutup) --}}
                @auth
                    <a href="{{ route('reservations.create', ['restaurant_id' => $restaurant->id]) }}" 
                       class="block w-full text-center px-6 py-4 bg-orange-600 text-white text-lg font-bold rounded-lg hover:bg-orange-700 transition shadow-lg">
                        📅 Buat Reservasi & Pre-Order Menu
                    </a>
                    <p class="text-center text-sm text-gray-500 mt-2">
                        💡 Reservasi untuk jam operasional: {{ $restaurant->formatted_opening_hours }} - {{ $restaurant->formatted_closing_hours }}
                    </p>
                @else
                    <a href="{{ route('login') }}" 
                       class="block w-full text-center px-6 py-4 bg-orange-600 text-white text-lg font-bold rounded-lg hover:bg-orange-700 transition shadow-lg">
                        🔐 Login untuk Reservasi
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Menu Section --}}
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">
            📋 Daftar Menu
        </h2>

        @if($restaurant->menus->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($restaurant->menus as $menu)
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-orange-500 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-800 mb-1">
                                    {{ $menu->name }}
                                </h3>
                                @if($menu->description)
                                    <p class="text-gray-600 text-sm mb-2">
                                        {{ $menu->description }}
                                    </p>
                                @endif
                            </div>
                            <div class="ml-4 text-right">
                                <p class="text-xl font-bold text-orange-600">
                                    {{ $menu->formatted_price }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- CTA Bottom --}}
            <div class="mt-8 text-center">
                <hr class="mb-6">
                <p class="text-gray-600 mb-4">Tertarik dengan menu di atas? Pesan sekarang!</p>
                @auth
                    <a href="{{ route('reservations.create', ['restaurant_id' => $restaurant->id]) }}" 
                       class="inline-block px-8 py-3 bg-orange-600 text-white font-bold rounded-lg hover:bg-orange-700 transition shadow-lg">
                        📅 Buat Reservasi Sekarang
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="inline-block px-8 py-3 bg-orange-600 text-white font-bold rounded-lg hover:bg-orange-700 transition shadow-lg">
                        🔐 Login untuk Reservasi
                    </a>
                @endauth
            </div>
        @else
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-8 text-center">
                <div class="text-5xl mb-3">ℹ️</div>
                <p class="text-gray-600">Menu untuk restoran ini belum tersedia.</p>
            </div>
        @endif
    </div>

    {{-- Back Button --}}
    <div class="mt-6">
        <a href="{{ route('restaurants.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">
            ← Kembali ke Daftar Restoran
        </a>
    </div>
</div>
@endsection