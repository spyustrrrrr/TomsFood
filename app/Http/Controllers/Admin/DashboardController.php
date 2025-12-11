<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * File: app/Http/Controllers/Admin/DashboardController.php (UPDATED)
     */
    public function index()
    {
        // Statistics
        $stats = [
            'total_users' => User::where('role', 'customer')->count(),
            'total_restaurants' => Restaurant::count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'today_reservations' => Reservation::whereDate('reservation_date', today())->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        // Revenue today
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total');
        
        $stats['today_revenue'] = $todayRevenue;
        $stats['today_commission'] = $todayRevenue * 0.07; // 7% commission

        // Monthly revenue
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total');
        
        $stats['monthly_revenue'] = $monthlyRevenue;
        $stats['monthly_commission'] = $monthlyRevenue * 0.07;

        // Recent reservations (today and upcoming)
        $recent_reservations = Reservation::with(['customer', 'restaurant'])
            ->where('reservation_date', '>=', now())
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->orderBy('reservation_date', 'asc')
            ->limit(10)
            ->get();

        // Recent orders
        $recent_orders = Order::with(['customer', 'restaurant'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Chart data: Reservations per day (last 7 days)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Reservation::whereDate('reservation_date', $date)->count();
            $chartData['dates'][] = $date->format('d M');
            $chartData['counts'][] = $count;
        }

        return view('admin.dashboard', compact(
            'stats', 
            'recent_reservations', 
            'recent_orders',
            'chartData'
        ));
    }
}