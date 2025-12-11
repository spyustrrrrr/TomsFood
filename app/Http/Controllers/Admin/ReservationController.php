<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * File: app/Http/Controllers/Admin/ReservationController.php
     * 
     * Display all reservations
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['customer', 'restaurant', 'order']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by restaurant
        if ($request->has('restaurant_id') && $request->restaurant_id != '') {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('reservation_date', $request->date);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'reservation_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reservations = $query->paginate(20);
        $restaurants = Restaurant::orderBy('name')->get();

        return view('admin.reservations.index', compact('reservations', 'restaurants'));
    }

    /**
     * Display the specified reservation
     */
    public function show($id)
    {
        $reservation = Reservation::with([
            'customer',
            'restaurant',
            'order.orderItems.menu',
            'order.payment'
        ])->findOrFail($id);
        
        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Update reservation status
     */
    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,customer_arrived,completed,cancelled,no_show',
            'table_number' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // Update reservation
            $reservation->update([
                'status' => $validated['status'],
                'table_number' => $validated['table_number'] ?? $reservation->table_number,
            ]);

            // Update order status sesuai reservation status
            if ($reservation->order) {
                $orderStatus = $this->mapReservationStatusToOrderStatus($validated['status']);
                $reservation->order->update(['status' => $orderStatus]);
            }

            DB::commit();

            return back()->with('success', 'Status reservasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Confirm reservation
     */
    public function confirm($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Hanya reservasi pending yang dapat dikonfirmasi.');
        }

        DB::beginTransaction();
        try {
            $reservation->update(['status' => 'confirmed']);
            
            if ($reservation->order) {
                $reservation->order->update(['status' => 'confirmed']);
            }

            DB::commit();
            return back()->with('success', 'Reservasi berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengkonfirmasi reservasi.');
        }
    }

    /**
     * Assign table to reservation
     */
    public function assignTable(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'table_number' => 'required|string|max:20',
        ]);

        $reservation->update([
            'table_number' => $validated['table_number'],
        ]);

        return back()->with('success', 'Nomor meja berhasil ditentukan.');
    }

    /**
     * Mark customer as arrived
     */
    public function markArrived($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (!in_array($reservation->status, ['confirmed', 'preparing', 'ready'])) {
            return back()->with('error', 'Status reservasi tidak valid.');
        }

        $reservation->update(['status' => 'customer_arrived']);

        return back()->with('success', 'Customer ditandai telah datang.');
    }

    /**
     * Complete reservation
     */
    public function complete($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'customer_arrived') {
            return back()->with('error', 'Reservasi harus dalam status customer arrived.');
        }

        DB::beginTransaction();
        try {
            $reservation->update(['status' => 'completed']);
            
            if ($reservation->order) {
                $reservation->order->update(['status' => 'completed']);
            }

            DB::commit();
            return back()->with('success', 'Reservasi selesai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan reservasi.');
        }
    }

    /**
     * Map reservation status to order status
     */
    private function mapReservationStatusToOrderStatus($reservationStatus)
    {
        $mapping = [
            'pending' => 'pending',
            'confirmed' => 'confirmed',
            'preparing' => 'preparing',
            'ready' => 'ready',
            'customer_arrived' => 'ready',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            'no_show' => 'cancelled',
        ];

        return $mapping[$reservationStatus] ?? 'pending';
    }

    /**
     * Get today's reservations (for dashboard widget)
     */
    public function todayReservations()
    {
        $reservations = Reservation::with(['customer', 'restaurant'])
            ->whereDate('reservation_date', today())
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->orderBy('reservation_date')
            ->get();

        return view('admin.reservations.today', compact('reservations'));
    }

    /**
     * Statistics
     */
    public function statistics(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $stats = [
            'total' => Reservation::whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending' => Reservation::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'pending')->count(),
            'confirmed' => Reservation::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'confirmed')->count(),
            'completed' => Reservation::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')->count(),
            'cancelled' => Reservation::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'cancelled')->count(),
            'no_show' => Reservation::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'no_show')->count(),
        ];

        // Revenue calculation
        $totalRevenue = Reservation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with('order')
            ->get()
            ->sum(function($reservation) {
                return $reservation->order ? $reservation->order->total : 0;
            });

        $stats['revenue'] = $totalRevenue;
        $stats['commission'] = $totalRevenue * 0.07; // 7% commission

        return view('admin.reservations.statistics', compact('stats', 'startDate', 'endDate'));
    }
}