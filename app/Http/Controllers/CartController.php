<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display cart items
     */
    public function index()
    {
        $cartItems = Cart::with(['menu', 'restaurant'])
            ->where('user_id', Auth::id())
            ->get();
        
        $total = $cartItems->sum('subtotal');
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        
        // Check if item already in cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('menu_id', $menu->id)
            ->first();

        if ($cartItem) {
            // Update quantity if already exists
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'menu_id' => $menu->id,
                'restaurant_id' => $menu->restaurant_id,
                'quantity' => $validated['quantity'],
                'price' => $menu->price,
            ]);
        }

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->update(['quantity' => $validated['quantity']]);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    /**
     * Remove item from cart
     */
    public function destroy($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Cart cleared!');
    }

    /**
     * Show checkout page
     */
    public function checkout()
    {
        $cartItems = Cart::with(['menu', 'restaurant'])
            ->where('user_id', Auth::id())
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        // Check if all items from same restaurant
        $restaurantIds = $cartItems->pluck('restaurant_id')->unique();
        if ($restaurantIds->count() > 1) {
            return redirect()->route('cart.index')
                ->with('error', 'You can only order from one restaurant at a time!');
        }

        $total = $cartItems->sum('subtotal');
        $restaurant = $cartItems->first()->restaurant;

        return view('cart.checkout', compact('cartItems', 'total', 'restaurant'));
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,transfer,e-wallet,credit_card',
            'delivery_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $cartItems = Cart::with(['menu', 'restaurant'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        // Check if all items from same restaurant
        $restaurantIds = $cartItems->pluck('restaurant_id')->unique();
        if ($restaurantIds->count() > 1) {
            return redirect()->route('cart.index')
                ->with('error', 'You can only order from one restaurant at a time!');
        }

        DB::beginTransaction();
        try {
            $total = $cartItems->sum('subtotal');
            $restaurant = $cartItems->first()->restaurant;

            // Create order
            $order = Order::create([
                'customer_id' => Auth::id(),
                'restaurant_id' => $restaurant->id,
                'total' => $total,
                'status' => 'pending',
            ]);

            // Create order items from cart
            foreach ($cartItems as $cartItem) {
                $order->orderItems()->create([
                    'menu_id' => $cartItem->menu_id,
                    'menu_name' => $cartItem->menu->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);
            }

            // Create payment record
            $paymentStatus = $validated['payment_method'] === 'cash' ? 'pending' : 'pending';
            
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'total_paid' => $total,
                'payment_time' => null, // Will be updated when payment confirmed
            ]);

            // Clear cart after successful order
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully! Please complete your payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }

    /**
     * Get cart count (for navbar badge)
     */
    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}