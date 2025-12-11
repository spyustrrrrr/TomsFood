@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

    @if($cartItems->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 text-lg mb-4">Your cart is empty</p>
            <a href="{{ route('home') }}" class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600">
                Browse Restaurants
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    @foreach($cartItems as $item)
                        <div class="p-4 border-b flex items-center justify-between">
                            <div class="flex items-center space-x-4 flex-1">
                                <div>
                                    <h3 class="font-semibold">{{ $item->menu->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $item->restaurant->name }}</p>
                                    <p class="text-sm font-medium text-orange-600">{{ $item->formatted_price }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <!-- Quantity Control -->
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                           min="1" max="99"
                                           class="w-16 px-2 py-1 border rounded text-center"
                                           onchange="this.form.submit()">
                                </form>

                                <div class="text-right">
                                    <p class="font-semibold">{{ $item->formatted_subtotal }}</p>
                                </div>

                                <!-- Remove Button -->
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Clear Cart Button -->
                <div class="mt-4">
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?')">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Tax (10%)</span>
                            <span>Rp {{ number_format($total * 0.1, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t pt-2 flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span>Rp {{ number_format($total * 1.1, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('cart.checkout') }}" 
                       class="block w-full bg-orange-600 text-white text-center py-3 rounded-lg hover:bg-blue-600 font-semibold">
                        Proceed to Checkout
                    </a>

                    <a href="{{ route('home') }}" 
                       class="block w-full text-center text-orange-500 py-2 mt-2 hover:text-blue-700">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection