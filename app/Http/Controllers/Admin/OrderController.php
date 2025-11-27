<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'restaurant']);
        
        // Filter by restaurant if selected
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }
        
        // Filter by status if selected
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        $restaurants = Restaurant::all(); // For filter dropdown
        
        return view('admin.orders.index', compact('orders', 'restaurants'));
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'restaurant', 'orderItems.menu', 'payment'])
            ->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated successfully!');
    }
}