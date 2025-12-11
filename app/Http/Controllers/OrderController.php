<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index()
    {
        $orders = Order::with(['restaurant', 'orderItems'])
            ->where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with(['restaurant', 'orderItems.menu', 'payment'])
            ->where('customer_id', Auth::id())
            ->findOrFail($id);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total
            $total = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $subtotal = $menu->price * $item['quantity'];
                $total += $subtotal;

                $orderItemsData[] = [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                ];
            }

            // Create order
            $order = Order::create([
                'customer_id' => Auth::id(),
                'restaurant_id' => $validated['restaurant_id'],
                'total' => $total,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit();

            // Handle JSON request (AJAX)
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully!',
                    'order_id' => $order->id
                ]);
            }

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create order: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Cancel order
     */
    public function cancel($id)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order cancelled successfully!');
    }
}