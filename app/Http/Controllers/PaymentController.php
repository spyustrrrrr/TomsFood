<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * File: app/Http/Controllers/PaymentController.php
     * Payment Gateway dengan Midtrans
     */
    
    public function __construct()
    {
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Show payment page untuk reservation
     */
    public function show($reservationId)
    {
        $reservation = Reservation::with(['restaurant', 'order.orderItems'])
            ->where('customer_id', Auth::id())
            ->findOrFail($reservationId);

        // Cek apakah sudah ada payment
        if ($reservation->order->payment && $reservation->order->payment->payment_status === 'paid') {
            return redirect()->route('reservations.show', $reservationId)
                ->with('error', 'Reservasi ini sudah dibayar.');
        }

        return view('payments.show', compact('reservation'));
    }

    /**
     * Create Snap Token untuk payment
     */
    public function createToken($reservationId)
    {
        $reservation = Reservation::with(['restaurant', 'order.orderItems', 'customer'])
            ->where('customer_id', Auth::id())
            ->findOrFail($reservationId);

        $order = $reservation->order;

        // Transaction details
        $transactionDetails = [
            'order_id' => 'RES-' . $reservation->id . '-' . time(),
            'gross_amount' => (int) $order->total,
        ];

        // Item details
        $itemDetails = [];
        foreach ($order->orderItems as $item) {
            $itemDetails[] = [
                'id' => $item->menu_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => $item->menu_name,
            ];
        }

        // Customer details
        $customerDetails = [
            'first_name' => $reservation->customer->first_name,
            'last_name' => $reservation->customer->last_name,
            'email' => $reservation->customer->email,
            'phone' => $reservation->customer->phone ?? '08123456789',
        ];

        // Transaction data
        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'enabled_payments' => [
                'credit_card', 
                'gopay', 
                'shopeepay', 
                'other_qris',
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'other_va',
            ],
            'callbacks' => [
                'finish' => route('payment.finish', $reservation->id),
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($transactionData);
            
            // Create or update payment record
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method' => 'midtrans',
                    'payment_status' => 'pending',
                    'total_paid' => $order->total,
                    'snap_token' => $snapToken,
                ]
            );

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat payment token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Callback dari Midtrans (Notification Handler)
     */
    public function notification(Request $request)
    {
        try {
            $notif = new \Midtrans\Notification();
            
            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id; // Format: RES-{id}-{timestamp}
            $fraudStatus = $notif->fraud_status;

            // Extract reservation ID dari order_id
            preg_match('/RES-(\d+)-/', $orderId, $matches);
            $reservationId = $matches[1] ?? null;

            if (!$reservationId) {
                Log::error('Invalid order_id format: ' . $orderId);
                return response()->json(['status' => 'error']);
            }

            $reservation = Reservation::with('order.payment')->findOrFail($reservationId);
            $payment = $reservation->order->payment;

            DB::beginTransaction();
            try {
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $payment->update([
                            'payment_status' => 'paid',
                            'payment_time' => now(),
                        ]);
                        $reservation->update(['status' => 'confirmed']);
                        $reservation->order->update(['status' => 'confirmed']);
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $payment->update([
                        'payment_status' => 'paid',
                        'payment_time' => now(),
                    ]);
                    $reservation->update(['status' => 'confirmed']);
                    $reservation->order->update(['status' => 'confirmed']);
                } elseif ($transactionStatus == 'pending') {
                    $payment->update(['payment_status' => 'pending']);
                } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                    $payment->update(['payment_status' => 'failed']);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Payment Update Error: ' . $e->getMessage());
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Notification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Finish page setelah payment (redirect dari Midtrans)
     */
    public function finish($reservationId)
    {
        $reservation = Reservation::with(['order.payment'])
            ->where('customer_id', Auth::id())
            ->findOrFail($reservationId);

        return redirect()->route('reservations.show', $reservationId)
            ->with('success', 'Pembayaran berhasil! Reservasi Anda sudah dikonfirmasi.');
    }

    /**
     * Check payment status (AJAX)
     */
    public function checkStatus($reservationId)
    {
        $reservation = Reservation::with('order.payment')
            ->where('customer_id', Auth::id())
            ->findOrFail($reservationId);

        return response()->json([
            'status' => $reservation->order->payment->payment_status ?? 'pending',
            'reservation_status' => $reservation->status,
        ]);
    }
}