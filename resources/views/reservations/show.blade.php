{{-- 
    File: resources/views/reservations/show.blade.php
    Reservation Detail Page
--}}

@extends('layouts.app')

@section('title', 'Detail Reservasi #' . $reservation->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('reservations.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-orange-600 font-medium">
                ← Kembali ke Daftar Reservasi
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <p class="text-green-700">✓ {{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <p class="text-red-700">✗ {{ session('error') }}</p>
            </div>
        @endif

        {{-- Status Card --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-orange-600 text-white p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold">📋 Reservasi #{{ $reservation->id }}</h2>
                    {!! \App\Models\Reservation::getStatusBadge($reservation->status) !!}
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Restoran</h3>
                        <p class="text-xl font-bold text-gray-800">{{ $reservation->restaurant->name }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Status</h3>
                        <p class="text-xl font-bold text-gray-800">
                            {{ \App\Models\Reservation::getAllStatuses()[$reservation->status] }}
                        </p>
                    </div>
                </div>

                <hr class="my-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                        <span class="text-2xl mr-3">📅</span>
                        <div>
                            <p class="font-semibold text-gray-800">Tanggal & Waktu</p>
                            <p class="text-gray-600">{{ $reservation->formatted_date }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <span class="text-2xl mr-3">👥</span>
                        <div>
                            <p class="font-semibold text-gray-800">Jumlah Tamu</p>
                            <p class="text-gray-600">{{ $reservation->guest_count }} orang</p>
                        </div>
                    </div>

                    @if($reservation->table_number)
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">🪑</span>
                            <div>
                                <p class="font-semibold text-gray-800">Nomor Meja</p>
                                <p class="text-2xl font-bold text-orange-600">{{ $reservation->table_number }}</p>
                            </div>
                        </div>
                    @endif

                    @if($reservation->special_request)
                        <div class="md:col-span-2 flex items-start">
                            <span class="text-2xl mr-3">💬</span>
                            <div>
                                <p class="font-semibold text-gray-800">Permintaan Khusus</p>
                                <p class="text-gray-600 italic">{{ $reservation->special_request }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <hr class="my-6">

                <div class="text-sm text-gray-500">
                    ⏰ Dibuat: {{ $reservation->created_at->translatedFormat('d F Y, H:i') }}
                </div>

                @if(!$reservation->isPast() && $reservation->canBeCancelled())
                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">
                            ⚠️ <strong>Perhatian:</strong> Reservasi hanya dapat dibatalkan maksimal 2 jam sebelum waktu kedatangan.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pre-Order Items --}}
        @if($reservation->order && $reservation->order->orderItems->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-green-600 text-white p-4">
                    <h3 class="text-xl font-bold">🛒 Pre-Order Menu</h3>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($reservation->order->orderItems as $item)
                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800">{{ $item->menu_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $item->quantity }} x {{ $item->formatted_total }}</p>
                                </div>
                                <p class="text-lg font-bold text-gray-800">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t-2 border-gray-300">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">Total:</span>
                            <span class="text-3xl font-bold text-orange-600">
                                {{ $reservation->order->formatted_total }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Restaurant Info --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-gray-800 text-white p-4">
                <h3 class="text-xl font-bold">🏪 Informasi Restoran</h3>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">⏰ Jam Operasional</p>
                        <p class="text-gray-800 font-medium">
                            {{ $reservation->restaurant->formatted_opening_hours }} - 
                            {{ $reservation->restaurant->formatted_closing_hours }}
                        </p>
                    </div>

                    @if($reservation->restaurant->phone)
                        <div>
                            <p class="text-sm text-gray-600">📞 Telepon</p>
                            <a href="tel:{{ $reservation->restaurant->phone }}" 
                               class="text-orange-600 hover:text-orange-700 font-medium">
                                {{ $reservation->restaurant->phone }}
                            </a>
                        </div>
                    @endif

                    @if($reservation->restaurant->address)
                        <div>
                            <p class="text-sm text-gray-600">📍 Alamat</p>
                            <p class="text-gray-800">{{ $reservation->restaurant->address }}</p>
                            @if($reservation->restaurant->map_link)
                                <a href="{{ $reservation->restaurant->map_link }}" 
                                   target="_blank"
                                   class="inline-block mt-2 text-orange-600 hover:text-orange-700 text-sm font-medium">
                                    🗺️ Lihat di Google Maps →
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="space-y-3">
                @if($reservation->canBeCancelled() && !$reservation->isPast())
                    <form action="{{ route('reservations.cancel', $reservation->id) }}" 
                          method="POST"
                          onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?')">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700 transition">
                            ✗ Batalkan Reservasi
                        </button>
                    </form>
                @endif

                @if($reservation->isConfirmed() || $reservation->isReady())
                    <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4 text-center">
                        <p class="text-green-700 font-bold">
                            ✓ Reservasi Dikonfirmasi - Silakan Datang ke Restoran
                        </p>
                    </div>
                @endif

                @if($reservation->isCompleted())
                    <a href="{{ route('restaurants.show', $reservation->restaurant_id) }}" 
                       class="block w-full text-center bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition">
                        ⭐ Beri Rating & Review
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection