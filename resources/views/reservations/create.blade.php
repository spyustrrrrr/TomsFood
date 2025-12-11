{{-- 
    File: resources/views/reservations/create.blade.php
    Form Reservasi + Pre-Order dengan Tailwind CSS
--}}

@extends('layouts.app')

@section('title', 'Buat Reservasi - ' . $restaurant->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Restaurant Info Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md sticky top-4">
                     @if($restaurant->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-full h-48 object-cover rounded">
                        
                    </div>
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center rounded-t-lg">
                            <span class="text-6xl">🍽️</span>
                        </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $restaurant->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ $restaurant->description }}</p>

                        <hr class="my-4">

                        <div class="space-y-3 text-sm">
                            <div class="flex items-start">
                                <span class="mr-2">🕒</span>
                                <div>
                                    <strong>Jam Buka:</strong><br>
                                    {{ $restaurant->formatted_opening_hours }} - {{ $restaurant->formatted_closing_hours }}
                                </div>
                            </div>

                            <div class="flex items-start">
                                <span class="mr-2">👥</span>
                                <div>
                                    <strong>Kapasitas:</strong> {{ $restaurant->table_capacity }} orang
                                </div>
                            </div>

                            @if($restaurant->phone)
                                <div class="flex items-start">
                                    <span class="mr-2">📞</span>
                                    <div>
                                        <strong>Telepon:</strong> {{ $restaurant->phone }}
                                    </div>
                                </div>
                            @endif

                            @if($restaurant->address)
                                <div class="flex items-start">
                                    <span class="mr-2">📍</span>
                                    <div>
                                        <strong>Alamat:</strong><br>
                                        {{ $restaurant->address }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reservation Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        📅 Buat Reservasi
                    </h2>

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('reservations.store') }}" method="POST" id="reservationForm">
                        @csrf
                        <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

                        {{-- Step 1: Reservation Details --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-orange-500">
                                1. Detail Reservasi
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Date & Time --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal & Waktu Kedatangan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" 
                                           name="reservation_date" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('reservation_date') border-red-500 @enderror"
                                           value="{{ old('reservation_date') }}"
                                           min="{{ now()->addHours($restaurant->booking_advance_hours)->format('Y-m-d\TH:i') }}"
                                           required>
                                    @error('reservation_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1">
                                        Minimal booking {{ $restaurant->booking_advance_hours }} jam sebelumnya
                                    </p>
                                </div>

                                {{-- Guest Count --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah Tamu <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="guest_count" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('guest_count') border-red-500 @enderror"
                                           value="{{ old('guest_count', 2) }}"
                                           min="1" 
                                           max="50"
                                           required>
                                    @error('guest_count')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Special Request --}}
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Permintaan Khusus (Opsional)
                                </label>
                                <textarea name="special_request" 
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('special_request') border-red-500 @enderror"
                                          placeholder="Contoh: Alergi makanan, kebutuhan khusus, dll.">{{ old('special_request') }}</textarea>
                                @error('special_request')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Step 2: Pre-Order Menu --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-orange-500">
                                2. Pre-Order Menu <span class="text-red-500">*</span>
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Pilih menu yang ingin Anda pesan sebelum datang
                            </p>

                            <div class="space-y-3">
                                @foreach($restaurant->menus as $menu)
                                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-orange-500 transition">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h4 class="font-bold text-gray-800">{{ $menu->name }}</h4>
                                                @if($menu->description)
                                                    <p class="text-sm text-gray-600">{{ $menu->description }}</p>
                                                @endif
                                                <p class="font-bold text-orange-600 mt-1">{{ $menu->formatted_price }}</p>
                                            </div>

                                            {{-- Quantity Selector --}}
                                            <div class="flex items-center space-x-2 ml-4">
                                                <button type="button" 
                                                        onclick="decreaseQty({{ $menu->id }})"
                                                        class="w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300 transition">
                                                    -
                                                </button>
                                                <input type="number" 
                                                       id="menu_{{ $menu->id }}_qty"
                                                       class="w-16 text-center border border-gray-300 rounded-lg menu-qty"
                                                       value="0" 
                                                       min="0" 
                                                       max="20"
                                                       data-menu-id="{{ $menu->id }}"
                                                       data-price="{{ $menu->price }}"
                                                       data-name="{{ $menu->name }}"
                                                       readonly>
                                                <button type="button" 
                                                        onclick="increaseQty({{ $menu->id }})"
                                                        class="w-8 h-8 bg-orange-600 text-white rounded-full hover:bg-orange-700 transition">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('menus')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Order Summary --}}
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 mb-6">
                            <h3 class="font-bold text-gray-800 mb-4">📋 Ringkasan Pesanan</h3>
                            <div id="orderSummary" class="mb-4">
                                <p class="text-gray-600 text-sm">Belum ada menu dipilih</p>
                            </div>
                            <hr class="border-gray-300 my-4">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-800 text-lg">Total:</span>
                                <span class="font-bold text-orange-600 text-2xl" id="totalPrice">Rp 0</span>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="space-y-3">
                            <button type="submit" 
                                    id="submitBtn"
                                    disabled
                                    class="w-full bg-orange-600 text-white font-bold py-4 rounded-lg hover:bg-orange-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                                ✓ Buat Reservasi
                            </button>
                            <a href="{{ route('restaurants.show', $restaurant->id) }}" 
                               class="block w-full text-center border-2 border-gray-300 text-gray-700 font-medium py-3 rounded-lg hover:bg-gray-50 transition">
                                ← Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Calculate total and update summary
function updateOrderSummary() {
    let total = 0;
    let items = [];
    let hasItems = false;

    document.querySelectorAll('.menu-qty').forEach(input => {
        const qty = parseInt(input.value) || 0;
        if (qty > 0) {
            hasItems = true;
            const menuId = input.dataset.menuId;
            const menuName = input.dataset.name;
            const price = parseFloat(input.dataset.price);
            const subtotal = qty * price;
            total += subtotal;
            items.push({ menuId, qty, menuName, price, subtotal });
        }
    });

    // Update summary display
    const summaryDiv = document.getElementById('orderSummary');
    if (items.length === 0) {
        summaryDiv.innerHTML = '<p class="text-gray-600 text-sm">Belum ada menu dipilih</p>';
    } else {
        let html = '<ul class="space-y-2">';
        items.forEach(item => {
            html += `<li class="flex justify-between text-sm">
                <span>${item.qty}x ${item.menuName}</span>
                <span class="font-medium">Rp ${item.subtotal.toLocaleString('id-ID')}</span>
            </li>`;
        });
        html += '</ul>';
        summaryDiv.innerHTML = html;
    }

    // Update total
    document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');

    // Enable/disable submit button
    document.getElementById('submitBtn').disabled = !hasItems;

    // Update form inputs
    updateFormInputs(items);
}

function updateFormInputs(items) {
    // Remove existing inputs
    document.querySelectorAll('input[name^="menus["]').forEach(el => el.remove());

    // Add new inputs
    const form = document.getElementById('reservationForm');
    items.forEach((item, index) => {
        const menuIdInput = document.createElement('input');
        menuIdInput.type = 'hidden';
        menuIdInput.name = `menus[${index}][menu_id]`;
        menuIdInput.value = item.menuId;
        form.appendChild(menuIdInput);

        const qtyInput = document.createElement('input');
        qtyInput.type = 'hidden';
        qtyInput.name = `menus[${index}][quantity]`;
        qtyInput.value = item.qty;
        form.appendChild(qtyInput);
    });
}

function increaseQty(menuId) {
    const input = document.getElementById(`menu_${menuId}_qty`);
    const max = parseInt(input.max);
    const current = parseInt(input.value) || 0;
    if (current < max) {
        input.value = current + 1;
        updateOrderSummary();
    }
}

function decreaseQty(menuId) {
    const input = document.getElementById(`menu_${menuId}_qty`);
    const current = parseInt(input.value) || 0;
    if (current > 0) {
        input.value = current - 1;
        updateOrderSummary();
    }
}

// Listen to manual input changes
document.querySelectorAll('.menu-qty').forEach(input => {
    input.addEventListener('change', updateOrderSummary);
});

// Initialize
updateOrderSummary();
</script>
@endsection