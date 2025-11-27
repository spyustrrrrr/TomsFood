@extends('layouts.admin')

@section('title', 'Manage Orders')
@section('page-title', 'Orders Management')

@section('content')
<div class="mb-6">
    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-4 items-center bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Restaurant</label>
            <select name="restaurant_id" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" onchange="this.form.submit()">
                <option value="">All Restaurants</option>
                @foreach($restaurants as $restaurant)
                    <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                        {{ $restaurant->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        
        @if(request('restaurant_id') || request('status'))
            <div class="pt-5">
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-600 hover:text-gray-800 underline">
                    Clear Filters
                </a>
            </div>
        @endif
    </form>
</div>
<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Restaurant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->customer->full_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->restaurant->name }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $order->formatted_total }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'completed') bg-green-100 text-green-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:underline">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection