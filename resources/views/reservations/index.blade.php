{{-- 
    File: resources/views/reservations/index.blade.php
    Customer Reservations List
--}}

@extends('layouts.app')

@section('title', 'Reservasi Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">📅 Reservasi Saya</h2>
            <a href="{{ route('restaurants.index') }}" 
               class="bg-orange-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-orange-700 transition">
                + Buat Reservasi Baru
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

        {{-- Reservations List --}}
        @if($reservations->count() > 0)
            <div class="space-y-4">
                @foreach($reservations as $reservation)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="md:flex">
                            {{-- Status Sidebar --}}
                            <div class="md:w-32 bg-gradient-to-b from-{{ $reservation->status == 'completed' ? 'green' : ($reservation->status == 'cancelled' ? 'red' : 'orange') }}-500 to-{{ $reservation->status == 'completed' ? 'green' : ($reservation->status == 'cancelled' ? 'red' : 'orange') }}-600 p-4 flex flex-col items-center justify-center text-white">
                                <div class="text-4xl mb-2">
                                    @if($reservation->status == 'completed') ✓
                                    @elseif($reservation->status == 'cancelled') ✗
                                    @elseif($reservation->status == 'confirmed') 👍
                                    @else ⏰
                                    @endif
                                </div>
                                <p class="text-xs text-center font-medium">
                                    {{ \App\Models\Reservation::getAllStatuses()[$reservation->status] }}
                                </p>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">
                                            {{ $reservation->restaurant->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">Reservasi #{{ $reservation->id }}</p>
                                    </div>
                                    <div class="text-right">
                                        @if($reservation->order)
                                            <p class="text-2xl font-bold text-orange-600">
                                                {{ $reservation->order->formatted_total }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-600">📅 <strong>Tanggal & Waktu:</strong></p>
                                        <p class="text-gray-800">{{ $reservation->formatted_date }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-600">👥 <strong>Jumlah Tamu:</strong></p>
                                        <p class="text-gray-800">{{ $reservation->guest_count }} orang</p>
                                    </div>

                                    @if($reservation->table_number)
                                        <div>
                                            <p class="text-sm text-gray-600">🪑 <strong>Nomor Meja:</strong></p>
                                            <p class="text-gray-800 font-bold">{{ $reservation->table_number }}</p>
                                        </div>
                                    @endif

                                    @if($reservation->special_request)
                                        <div class="md:col-span-2">
                                            <p class="text-sm text-gray-600">💬 <strong>Catatan:</strong></p>
                                            <p class="text-gray-800 italic">{{ $reservation->special_request }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Time Until --}}
                                @if(!$reservation->isPast())
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                        <p class="text-sm text-blue-700">
                                            ⏱️ <strong>{{ $reservation->time_until }}</strong>
                                        </p>
                                    </div>
                                @endif

                                {{-- Actions --}}
                                <div class="flex gap-2">
                                    <a href="{{ route('reservations.show', $reservation->id) }}" 
                                       class="flex-1 text-center px-4 py-2 border-2 border-orange-600 text-orange-600 rounded-lg font-medium hover:bg-orange-50 transition">
                                        👁️ Lihat Detail
                                    </a>

                                    @if($reservation->canBeCancelled() && !$reservation->isPast())
                                        <form action="{{ route('reservations.cancel', $reservation->id) }}" 
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin membatalkan reservasi?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-6 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                                                ✗ Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $reservations->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-8xl mb-4">📅</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Reservasi</h3>
                <p class="text-gray-600 mb-6">
                    Anda belum memiliki reservasi. Mulai pesan restoran favorit Anda sekarang!
                </p>
                <a href="{{ route('restaurants.index') }}" 
                   class="inline-block px-8 py-3 bg-orange-600 text-white rounded-lg font-bold hover:bg-orange-700 transition">
                    🔍 Cari Restoran
                </a>
            </div>
        @endif
    </div>
</div>
@endsection