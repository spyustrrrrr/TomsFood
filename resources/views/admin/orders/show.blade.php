@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order #' . $order->id . ' Details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Info -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Order Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Customer</p>
                    <p class="font-semibold">{{ $order->customer->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Restaurant</p>
                    <p class="font-semibold">{{ $order->restaurant->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Order Date</p>
                    <p class="font-semibold">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'completed') bg-green-100 text-green-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Order Items</h3>
            <table class="w-full">
                <thead class="border-b">
                    <tr>
                        <th class="text-left py-2">Item</th>
                        <th class="text-center py-2">Qty</th>
                        <th class="text-right py-2">Price</th>
                        <th class="text-right py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr class="border-b">
                        <td class="py-3">{{ $item->menu_name }}</td>
                        <td class="text-center py-3">{{ $item->quantity }}</td>
                        <td class="text-right py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right py-3 font-semibold">{{ $item->formatted_total }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold">
                        <td colspan="3" class="text-right py-3">Total:</td>
                        <td class="text-right py-3 text-lg">{{ $order->formatted_total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Update Status -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Update Status</h3>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-semibold mb-2">Order Status</label>
                    <select 
                        name="status" 
                        id="status"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    >
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
                    Update Status
                </button>
            </form>

            <a href="{{ route('admin.orders.index') }}" class="block text-center mt-4 text-gray-600 hover:underline">
                Back to Orders
            </a>
        </div>
    </div>
</div>
@endsection