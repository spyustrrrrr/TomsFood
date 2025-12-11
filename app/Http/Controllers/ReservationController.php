<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * File: app/Http/Controllers/ReservationController.php
     * 
     * Display a listing of user's reservations
     */
    public function index()
    {
        $reservations = Reservation::with(['restaurant', 'order.orderItems'])
            ->where('customer_id', Auth::id())
            ->orderBy('reservation_date', 'desc')
            ->paginate(10);
        
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new reservation
     */
    public function create(Request $request)
    {
        $restaurantId = $request->query('restaurant_id');
        
        if (!$restaurantId) {
            return redirect()->route('restaurants.index')
                ->with('error', 'Silakan pilih restoran terlebih dahulu.');
        }

        $restaurant = Restaurant::with('menus')->findOrFail($restaurantId);
        
        // Cek apakah restoran buka
        if ($restaurant->isClosed()) {
            return redirect()->route('restaurants.show', $restaurant->id)
                ->with('error', 'Restoran sedang tutup. Jam buka: ' . 
                    $restaurant->formatted_opening_hours . ' - ' . 
                    $restaurant->formatted_closing_hours);
        }

        return view('reservations.create', compact('restaurant'));
    }

    /**
     * Store a newly created reservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'reservation_date' => 'required|date|after:now',
            'guest_count' => 'required|integer|min:1|max:50',
            'special_request' => 'nullable|string|max:500',
            'menus' => 'required|array|min:1',
            'menus.*.menu_id' => 'required|exists:menus,id',
            'menus.*.quantity' => 'required|integer|min:1|max:20',
        ]);

        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);

        // Validasi: Cek minimal booking time
        $minBookingTime = now()->addHours($restaurant->booking_advance_hours);
        $reservationDate = Carbon::parse($validated['reservation_date']);

        if ($reservationDate->lt($minBookingTime)) {
            return back()->withInput()->with('error', 
                "Minimal booking {$restaurant->booking_advance_hours} jam sebelum kedatangan.");
        }

        // Validasi: Cek ketersediaan kapasitas
        if (!$restaurant->hasAvailableTable($reservationDate, $validated['guest_count'])) {
            return back()->withInput()->with('error', 
                'Maaf, kapasitas restoran untuk tanggal tersebut sudah penuh.');
        }

        // Validasi: Cek jam operasional
        $reservationTime = $reservationDate->format('H:i:s');
        if ($reservationTime < $restaurant->opening_hours || 
            $reservationTime > $restaurant->closing_hours) {
            return back()->withInput()->with('error', 
                'Jam reservasi harus dalam jam operasional restoran.');
        }

        DB::beginTransaction();
        try {
            // 1. Hitung total biaya pre-order
            $total = 0;
            $orderItemsData = [];

            foreach ($validated['menus'] as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                
                // Validasi menu dari restoran yang sama
                if ($menu->restaurant_id != $validated['restaurant_id']) {
                    throw new \Exception('Menu tidak sesuai dengan restoran.');
                }

                $subtotal = $menu->price * $item['quantity'];
                $total += $subtotal;

                $orderItemsData[] = [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                ];
            }

            // 2. Buat Reservasi
            $reservation = Reservation::create([
                'customer_id' => Auth::id(),
                'restaurant_id' => $validated['restaurant_id'],
                'reservation_date' => $validated['reservation_date'],
                'guest_count' => $validated['guest_count'],
                'special_request' => $validated['special_request'],
                'status' => 'pending',
            ]);

            // 3. Buat Order (Pre-order)
            $order = Order::create([
                'customer_id' => Auth::id(),
                'restaurant_id' => $validated['restaurant_id'],
                'reservation_id' => $reservation->id,
                'order_type' => 'pre_order',
                'total' => $total,
                'status' => 'pending',
                'preparation_time' => 30, // Default 30 menit
            ]);

            // 4. Simpan Order Items
            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit();

            return redirect()->route('reservations.show', $reservation->id)
                ->with('success', 'Reservasi berhasil dibuat! Menunggu konfirmasi restoran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal membuat reservasi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified reservation
     */
    public function show($id)
    {
        $reservation = Reservation::with([
            'restaurant', 
            'order.orderItems.menu',
            'order.payment'
        ])
        ->where('customer_id', Auth::id())
        ->findOrFail($id);
        
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Cancel reservation
     */
    public function cancel($id)
    {
        $reservation = Reservation::where('customer_id', Auth::id())
            ->findOrFail($id);
        
        // Cek apakah bisa dibatalkan
        if (!$reservation->canBeCancelled()) {
            return back()->with('error', 
                'Reservasi tidak dapat dibatalkan karena sudah dalam proses.');
        }

        // Cek minimal waktu pembatalan (misalnya 2 jam sebelum reservasi)
        $minCancelTime = $reservation->reservation_date->subHours(2);
        if (now()->gt($minCancelTime)) {
            return back()->with('error', 
                'Reservasi tidak dapat dibatalkan kurang dari 2 jam sebelum waktu kedatangan.');
        }

        DB::beginTransaction();
        try {
            // Update status reservasi
            $reservation->update(['status' => 'cancelled']);

            // Update status order jika ada
            if ($reservation->order) {
                $reservation->order->update(['status' => 'cancelled']);
            }

            DB::commit();

            return back()->with('success', 'Reservasi berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan reservasi.');
        }
    }

    /**
     * Check availability for a specific date/time
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'reservation_date' => 'required|date',
            'guest_count' => 'required|integer|min:1',
        ]);

        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);
        $available = $restaurant->hasAvailableTable(
            $validated['reservation_date'], 
            $validated['guest_count']
        );

        $remainingCapacity = $restaurant->getRemainingCapacity($validated['reservation_date']);

        return response()->json([
            'available' => $available,
            'remaining_capacity' => $remainingCapacity,
            'message' => $available 
                ? "Tersedia untuk {$validated['guest_count']} orang" 
                : "Kapasitas penuh untuk tanggal tersebut"
        ]);
    }
}