{{-- 
    File: resources/views/payments/show.blade.php
    Halaman Payment dengan Midtrans Snap
--}}

@extends('layouts.app')

@section('title', 'Pembayaran Reservasi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-orange-600 text-white p-6">
                <h2 class="text-2xl font-bold">💳 Pembayaran Reservasi</h2>
                <p class="text-orange-100">Reservasi #{{ $reservation->id }}</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Reservation Info --}}
                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Detail Reservasi</h3>
                        <div class="space-y-2 text-sm">
                            <p><strong>Restoran:</strong> {{ $reservation->restaurant->name }}</p>
                            <p><strong>Tanggal:</strong> {{ $reservation->formatted_date }}</p>
                            <p><strong>Tamu:</strong> {{ $reservation->guest_count }} orang</p>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Pre-Order Menu</h3>
                        <div class="space-y-1 text-sm mb-3">
                            @foreach($reservation->order->orderItems as $item)
                                <div class="flex justify-between">
                                    <span>{{ $item->quantity }}x {{ $item->menu_name }}</span>
                                    <span>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <hr class="my-3">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span class="text-orange-600">{{ $reservation->order->formatted_total }}</span>
                        </div>
                    </div>
                </div>

                <hr class="my-6">

                {{-- Payment Methods Info --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-bold text-gray-800 mb-2">💰 Metode Pembayaran yang Tersedia:</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                        <div class="flex items-center">
                            <span class="mr-2">💳</span>
                            <span>Credit Card</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">🏦</span>
                            <span>Bank Transfer</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">📱</span>
                            <span>GoPay</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">🛍️</span>
                            <span>ShopeePay</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">📲</span>
                            <span>QRIS</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">🏪</span>
                            <span>Alfamart/Indomaret</span>
                        </div>
                    </div>
                </div>

                {{-- Pay Button --}}
                <div class="space-y-3">
                    <button id="payButton" 
                            class="w-full bg-orange-600 text-white font-bold py-4 rounded-lg hover:bg-orange-700 transition">
                        💳 Bayar Sekarang - {{ $reservation->order->formatted_total }}
                    </button>
                    
                    <a href="{{ route('reservations.show', $reservation->id) }}" 
                       class="block w-full text-center border-2 border-gray-300 text-gray-700 font-medium py-3 rounded-lg hover:bg-gray-50 transition">
                        ← Kembali ke Detail Reservasi
                    </a>
                </div>

                {{-- Loading Indicator --}}
                <div id="loading" class="hidden text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-orange-600"></div>
                    <p class="text-gray-600 mt-2">Memuat halaman pembayaran...</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Midtrans Snap.js --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('payButton').addEventListener('click', function() {
    const button = this;
    const loading = document.getElementById('loading');
    
    // Disable button & show loading
    button.disabled = true;
    button.classList.add('opacity-50', 'cursor-not-allowed');
    loading.classList.remove('hidden');
    
    // Get Snap Token from backend
    fetch('{{ route("payment.create-token", $reservation->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        loading.classList.add('hidden');
        
        if (data.success) {
            // Open Midtrans Snap popup
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment Success:', result);
                    window.location.href = '{{ route("payment.finish", $reservation->id) }}';
                },
                onPending: function(result) {
                    console.log('Payment Pending:', result);
                    alert('Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
                    window.location.href = '{{ route("reservations.show", $reservation->id) }}';
                },
                onError: function(result) {
                    console.error('Payment Error:', result);
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });
        } else {
            alert('Gagal membuat pembayaran: ' + data.message);
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
        loading.classList.add('hidden');
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
    });
});
</script>
@endsection