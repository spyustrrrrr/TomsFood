{{-- 
    File: resources/views/home.blade.php
    Homepage dengan Tailwind CSS
--}}

@extends('layouts.app')

@section('title', 'Home - Toman Food')

@section('content')
{{-- Hero Section --}}
<div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
    <div class="container mx-auto px-4 py-20 text-center">
        <h1 class="text-5xl font-bold mb-4">
            🍽️ Selamat Datang di Toman Food
        </h1>
        <p class="text-xl mb-8 text-orange-100">
            Platform reservasi restoran dengan pre-order menu.<br>
            Mudah, cepat, dan praktis!
        </p>
        @guest
            <a href="{{ route('register') }}" class="bg-white text-orange-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                Daftar Sekarang →
            </a>
        @endguest
    </div>
</div>

{{-- Restaurants Section --}}
<div class="container mx-auto px-4 py-12">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">🏪 Daftar Restoran</h2>
        <a href="{{ route('restaurants.index') }}" class="text-orange-600 hover:text-orange-700 font-medium">
            Lihat Semua →
        </a>
    </div>

    @if($restaurants->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($restaurants as $restaurant)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    {{-- Restaurant Image --}}
                    @if($restaurant->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-full h-48 object-cover rounded">
                        
                    </div>
                @endif
                    
                    {{-- Restaurant Info --}}
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2 text-gray-800">{{ $restaurant->name }}</h3>
                        
                        {{-- Rating --}}
                        <div class="flex items-center mb-2">
                            <span class="text-yellow-500">
                                @for($i = 0; $i < floor($restaurant->rating); $i++)
                                    ⭐
                                @endfor
                            </span>
                            <span class="text-gray-600 text-sm ml-2">({{ number_format($restaurant->rating, 1) }})</span>
                        </div>

                        {{-- Description --}}
                        <p class="text-gray-600 text-sm mb-3">
                            {{ Str::limit($restaurant->description, 80) }}
                        </p>

                        {{-- Operating Hours --}}
                        <div class="text-xs text-gray-500 mb-3">
                            🕒 {{ $restaurant->formatted_opening_hours }} - {{ $restaurant->formatted_closing_hours }}
                        </div>

                        {{-- Status Badges --}}
                        <div class="flex gap-2 mb-4">
                            @if($restaurant->isOpen())
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">✓ Buka</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">✗ Tutup</span>
                            @endif
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">
                                👥 {{ $restaurant->table_capacity }}
                            </span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-2">
                            <a href="{{ route('restaurants.show', $restaurant->id) }}" 
                               class="block text-center px-4 py-2 border border-orange-600 text-orange-600 rounded-lg hover:bg-orange-50 transition">
                                👁️ Lihat Menu
                            </a>
                            
                            @auth
                                <a href="{{ route('reservations.create', ['restaurant_id' => $restaurant->id]) }}" 
                                   class="block text-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                                    📅 Reservasi
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="block text-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                                    🔐 Login untuk Reservasi
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
            <div class="text-6xl mb-4">ℹ️</div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum ada restoran</h3>
            <p class="text-gray-600">Restoran akan segera hadir!</p>
        </div>
    @endif
</div>

{{-- Features Section --}}
<div class="bg-gradient-to-b from-gray-50 to-white py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">
            ✨ Kenapa Toman Food?
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Feature 1 --}}
            <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-xl transition">
                <div class="text-6xl mb-4">📅</div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Reservasi Mudah</h3>
                <p class="text-gray-600">
                    Booking meja restoran favorit dengan mudah dan cepat langsung dari smartphone Anda.
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-xl transition">
                <div class="text-6xl mb-4">🛒</div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Pre-Order Menu</h3>
                <p class="text-gray-600">
                    Pesan menu sebelum datang, tidak perlu menunggu lama di restoran.
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-xl transition">
                <div class="text-6xl mb-4">💳</div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Pembayaran Digital</h3>
                <p class="text-gray-600">
                    Bayar dengan mudah menggunakan berbagai metode pembayaran digital yang aman.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- CTA Section --}}
<div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-4">🚀 Siap Mencoba Pengalaman Baru?</h2>
        <p class="text-xl mb-8 text-orange-100">
            Daftar sekarang dan nikmati kemudahan reservasi restoran dengan pre-order menu!
        </p>
        
        @guest
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" 
                   class="bg-white text-orange-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    📝 Daftar Gratis
                </a>
                <a href="{{ route('restaurants.index') }}" 
                   class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-orange-600 transition">
                    🔍 Lihat Restoran
                </a>
            </div>
        @else
            <a href="{{ route('restaurants.index') }}" 
               class="inline-block bg-white text-orange-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                🏪 Mulai Reservasi Sekarang
            </a>
        @endguest
    </div>
</div>
@endsection