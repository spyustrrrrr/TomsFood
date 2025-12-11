<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Show payment page
     */
    public function show($orderId)
    {
        $order = Order::with(['restaurant', 'orderItems', 'payment'])
            ->where('customer_id', Auth::id())
            ->findOrFail($orderId);

        if (!$order->payment) {
            return redirect()->route('orders.show', $orderId)
                ->with('error', 'Payment information not found!');
        }

        return view('payments.show', compact('order'));
    }

    /**
     * Confirm payment (manual confirmation by user)
     */
    public function confirm(Request $request, $orderId)
    {
        $validated = $request->validate([
            'payment_proof' => 'nullable|string|max:255', // URL atau file path bukti pembayaran
        ]);

        $order = Order::where('customer_id', Auth::id())->findOrFail($orderId);
        $payment = $order->payment;

        if (!$payment) {
            return back()->with('error', 'Payment record not found!');
        }

        if ($payment->payment_status === 'paid') {
            return back()->with('error', 'Payment already confirmed!');
        }

        // Update payment status
        $payment->update([
            'payment_status' => 'paid',
            'payment_time' => now(),
        ]);

        // Update order status
        $order->update(['status' => 'confirmed']);

        return redirect()->route('orders.show', $orderId)
            ->with('success', 'Payment confirmed! Your order is being processed.');
    }

    /**
     * Cancel payment
     */
    public function cancel($orderId)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($orderId);
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'Cannot cancel this payment!');
        }

        $payment = $order->payment;
        if ($payment) {
            $payment->update(['payment_status' => 'cancelled']);
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')
            ->with('success', 'Payment cancelled.');
    }

    /**
     * Payment methods info page
     */
    public function methods()
    {
        $methods = [
            [
                'id' => 'cash',
                'name' => 'Cash on Delivery (COD)',
                'description' => 'Pay with cash when your order arrives',
                'icon' => '💵',
                'processing_time' => 'Instant',
            ],
            [
                'id' => 'transfer',
                'name' => 'Bank Transfer',
                'description' => 'Transfer to our bank account',
                'icon' => '🏦',
                'processing_time' => '1-24 hours',
                'accounts' => [
                    'BCA: 1234567890 a.n. Restaurant App',
                    'Mandiri: 0987654321 a.n. Restaurant App',
                    'BNI: 5555666677 a.n. Restaurant App',
                ]
            ],
            [
                'id' => 'e-wallet',
                'name' => 'E-Wallet',
                'description' => 'Pay with GoPay, OVO, Dana, or ShopeePay',
                'icon' => '📱',
                'processing_time' => 'Instant',
            ],
            [
                'id' => 'credit_card',
                'name' => 'Credit/Debit Card',
                'description' => 'Pay with Visa, Mastercard, or JCB',
                'icon' => '💳',
                'processing_time' => 'Instant',
            ],
        ];

        return view('payments.methods', compact('methods'));
    }
}