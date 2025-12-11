@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Payment Methods</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($methods as $method)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <span class="text-4xl mr-4">{{ $method['icon'] }}</span>
                    <div>
                        <h2 class="text-xl font-bold">{{ $method['name'] }}</h2>
                        <p class="text-sm text-gray-500">{{ $method['description'] }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Processing Time:</span>
                        <span class="font-semibold">{{ $method['processing_time'] }}</span>
                    </div>
                </div>

                @if(isset($method['accounts']))
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="font-semibold mb-2">Bank Accounts:</p>
                        <ul class="space-y-1 text-sm">
                            @foreach($method['accounts'] as $account)
                                <li class="text-gray-700">{{ $account }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($method['id'] === 'transfer')
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg text-sm">
                        <p class="font-semibold text-blue-800 mb-2">Transfer Instructions:</p>
                        <ol class="list-decimal list-inside space-y-1 text-blue-700">
                            <li>Transfer the exact amount to the bank account</li>
                            <li>Use your Order ID as the transfer note</li>
                            <li>Save your transfer receipt</li>
                            <li>Confirm payment in your order page</li>
                        </ol>
                    </div>
                @endif

                @if($method['id'] === 'e-wallet')
                    <div class="mt-4 p-4 bg-green-50 rounded-lg text-sm">
                        <p class="font-semibold text-green-800 mb-2">E-Wallet Instructions:</p>
                        <ol class="list-decimal list-inside space-y-1 text-green-700">
                            <li>Select your preferred e-wallet app</li>
                            <li>Scan the QR code shown after placing order</li>
                            <li>Complete payment in your app</li>
                            <li>Payment will be confirmed automatically</li>
                        </ol>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <h3 class="font-bold text-lg mb-2">Important Notes:</h3>
        <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
            <li>Please complete payment within 24 hours to avoid order cancellation</li>
            <li>For COD orders, please prepare exact change</li>
            <li>For bank transfer, use your Order ID as the transfer note for faster verification</li>
            <li>Contact our support if you encounter any payment issues</li>
        </ul>
    </div>
</div>
@endsection