@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Checkout</h1>

    <form action="{{ route('cart.processCheckout') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Restaurant Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Ordering From</h2>
                    <div class="flex items-center space-x-4">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $restaurant->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $restaurant->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Your Order</h2>
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="font-medium">{{ $item->menu->name }}</p>
                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <p class="font-semibold">{{ $item->formatted_subtotal }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Payment Method</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cash" class="mr-3" required>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-2">💵</span>
                                    <div>
                                        <p class="font-semibold">Cash on Delivery (COD)</p>
                                        <p class="text-sm text-gray-500">Pay with cash when your order arrives</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="transfer" class="mr-3" required>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-2">🏦</span>
                                    <div>
                                        <p class="font-semibold">Bank Transfer</p>
                                        <p class="text-sm text-gray-500">Transfer to our bank account</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="e-wallet" class="mr-3" required>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-2">📱</span>
                                    <div>
                                        <p class="font-semibold">E-Wallet</p>
                                        <p class="text-sm text-gray-500">GoPay, OVO, Dana, ShopeePay</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="credit_card" class="mr-3" required>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-2">💳</span>
                                    <div>
                                        <p class="font-semibold">Credit/Debit Card</p>
                                        <p class="text-sm text-gray-500">Visa, Mastercard, JCB</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <a href="{{ route('payments.methods') }}" class="text-blue-500 text-sm mt-4 inline-block hover:underline">
                        View payment details →
                    </a>
                </div>

                <!-- Delivery Address -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Delivery Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Delivery Address</label>
                            <textarea name="delivery_address" rows="3" 
                                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Enter your complete delivery address"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Order Notes (Optional)</label>
                            <textarea name="notes" rows="2" 
                                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Any special instructions?"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary (Sticky) -->
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
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Delivery Fee</span>
                            <span>Rp 15,000</span>
                        </div>
                        <div class="border-t pt-2 flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span>Rp {{ number_format(($total * 1.1) + 15000, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 font-semibold">
                        Place Order
                    </button>

                    <a href="{{ route('cart.index') }}" 
                       class="block text-center text-blue-500 py-2 mt-2 hover:text-blue-700">
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection