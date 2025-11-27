@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Customers</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <span class="text-3xl">👥</span>
            </div>
        </div>
    </div>

    <!-- Total Restaurants -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Restaurants</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_restaurants'] }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <span class="text-3xl">🏪</span>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Orders</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <span class="text-3xl">📦</span>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Pending Orders</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_orders'] }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <span class="text-3xl">⏳</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <h3 class="text-xl font-semibold text-gray-800">Recent Orders</h3>
    </div>
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
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recent_orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->customer->full_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->restaurant->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->formatted_total }}</td>
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
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection