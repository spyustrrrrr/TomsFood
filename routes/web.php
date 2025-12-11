<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;

/*
|--------------------------------------------------------------------------
| File: routes/web.php (UPDATED)
|--------------------------------------------------------------------------
*/

// ============================================
// PUBLIC ROUTES
// ============================================

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Restaurant Routes (Public)
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

// Menu Routes (Public)
Route::get('/restaurants/{restaurantId}/menus', [MenuController::class, 'index'])->name('menus.index');
Route::get('/menus/{id}', [MenuController::class, 'show'])->name('menus.show');

// ============================================
// CUSTOMER ROUTES (Authenticated)
// ============================================

Route::middleware('auth')->group(function () {
    
    // Reservation Routes
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{id}', [ReservationController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [ReservationController::class, 'cancel'])->name('cancel');
        
        // AJAX: Check availability
        Route::post('/check-availability', [ReservationController::class, 'checkAvailability'])
            ->name('checkAvailability');
    });

    // Order Routes (Legacy - kept for backward compatibility)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::put('/{id}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });
});

// ============================================
// ADMIN ROUTES
// ============================================

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Restaurants Management
    Route::prefix('restaurants')->name('restaurants.')->group(function () {
        Route::get('/', [AdminRestaurantController::class, 'index'])->name('index');
        Route::get('/create', [AdminRestaurantController::class, 'create'])->name('create');
        Route::post('/', [AdminRestaurantController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminRestaurantController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminRestaurantController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminRestaurantController::class, 'destroy'])->name('destroy');
    });
    
    // Menus Management
    Route::prefix('menus')->name('menus.')->group(function () {
        Route::get('/', [AdminMenuController::class, 'index'])->name('index');
        Route::get('/create', [AdminMenuController::class, 'create'])->name('create');
        Route::post('/', [AdminMenuController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminMenuController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminMenuController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminMenuController::class, 'destroy'])->name('destroy');
    });
    
    // Orders Management (Legacy)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::put('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
    });
    
    // Reservations Management (NEW)
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [AdminReservationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminReservationController::class, 'show'])->name('show');
        Route::put('/{id}/status', [AdminReservationController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/confirm', [AdminReservationController::class, 'confirm'])->name('confirm');
        Route::post('/{id}/assign-table', [AdminReservationController::class, 'assignTable'])->name('assignTable');
        Route::post('/{id}/mark-arrived', [AdminReservationController::class, 'markArrived'])->name('markArrived');
        Route::post('/{id}/complete', [AdminReservationController::class, 'complete'])->name('complete');
        
        // Today's reservations widget
        Route::get('/today/widget', [AdminReservationController::class, 'todayReservations'])->name('today');
        
        // Statistics
        Route::get('/statistics', [AdminReservationController::class, 'statistics'])->name('statistics');
    });
});