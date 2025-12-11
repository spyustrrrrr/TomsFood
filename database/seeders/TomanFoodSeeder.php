<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Order;
use Carbon\Carbon;

class TomanFoodSeeder extends Seeder
{
    /**
     * File: database/seeders/TomanFoodSeeder.php (FIXED - Image URLs)
     * 
     * Run this seeder with: php artisan db:seed --class=TomanFoodSeeder
     */
    public function run(): void
    {
        // ============================================
        // 1. CREATE USERS (with check)
        // ============================================
        
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin123'),
                'email' => 'admin@tomanfood.com',
                'first_name' => 'Admin',
                'last_name' => 'Toman Food',
                'role' => 'admin',
            ]
        );

        $customer1 = User::firstOrCreate(
            ['username' => 'budi'],
            [
                'password' => Hash::make('password123'),
                'email' => 'budi@example.com',
                'first_name' => 'Budi',
                'last_name' => 'Santoso',
                'role' => 'customer',
            ]
        );

        $customer2 = User::firstOrCreate(
            ['username' => 'siti'],
            [
                'password' => Hash::make('password123'),
                'email' => 'siti@example.com',
                'first_name' => 'Siti',
                'last_name' => 'Nurhaliza',
                'role' => 'customer',
            ]
        );

        $customer3 = User::firstOrCreate(
            ['username' => 'andi'],
            [
                'password' => Hash::make('password123'),
                'email' => 'andi@example.com',
                'first_name' => 'Andi',
                'last_name' => 'Wijaya',
                'role' => 'customer',
            ]
        );

        // ============================================
        // 2. CREATE RESTAURANTS (FIXED IMAGE URLs)
        // ============================================
        
        $restaurant1 = Restaurant::firstOrCreate(
            ['name' => 'Warung Padang Sederhana'],
            [
                'description' => 'Restoran Padang dengan cita rasa autentik dan harga terjangkau. Spesialisasi masakan Minang yang lezat.',
                'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500', // Real image
                'rating' => 4.5,
                'table_capacity' => 40,
                'opening_hours' => '08:00:00',
                'closing_hours' => '21:00:00',
                'booking_advance_hours' => 2,
                'phone' => '0812-3456-7890',
                'address' => 'Jl. Pemuda No. 123, Semarang, Jawa Tengah',
                'latitude' => -6.9667,
                'longitude' => 110.4167,
            ]
        );

        $restaurant2 = Restaurant::firstOrCreate(
            ['name' => 'Sushi Zen'],
            [
                'description' => 'Restoran Jepang modern dengan menu sushi fresh dan autentik. Cocok untuk family dinner atau business meeting.',
                'image' => 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=500', // Real image
                'rating' => 4.8,
                'table_capacity' => 30,
                'opening_hours' => '11:00:00',
                'closing_hours' => '22:00:00',
                'booking_advance_hours' => 3,
                'phone' => '0813-4567-8901',
                'address' => 'Jl. Pandanaran No. 45, Semarang, Jawa Tengah',
                'latitude' => -6.9833,
                'longitude' => 110.4083,
            ]
        );

        $restaurant3 = Restaurant::firstOrCreate(
            ['name' => 'Bebek Goreng Pak Ndut'],
            [
                'description' => 'Spesialis bebek goreng dan bebek bakar dengan sambal yang menggugah selera. Menu favorit keluarga Indonesia.',
                'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=500', // Real image
                'rating' => 4.3,
                'table_capacity' => 50,
                'opening_hours' => '10:00:00',
                'closing_hours' => '21:00:00',
                'booking_advance_hours' => 2,
                'phone' => '0814-5678-9012',
                'address' => 'Jl. Gajah Mada No. 78, Semarang, Jawa Tengah',
                'latitude' => -6.9750,
                'longitude' => 110.4200,
            ]
        );

        $restaurant4 = Restaurant::firstOrCreate(
            ['name' => 'Pizza Bella Italia'],
            [
                'description' => 'Authentic Italian pizza dengan bahan import pilihan. Suasana cozy dan romantis cocok untuk date atau gathering.',
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=500', // Real image
                'rating' => 4.7,
                'table_capacity' => 25,
                'opening_hours' => '12:00:00',
                'closing_hours' => '23:00:00',
                'booking_advance_hours' => 4,
                'phone' => '0815-6789-0123',
                'address' => 'Jl. Simpang Lima No. 99, Semarang, Jawa Tengah',
                'latitude' => -6.9925,
                'longitude' => 110.4208,
            ]
        );

        // ============================================
        // 3. CREATE MENUS
        // ============================================
        
        Menu::where('restaurant_id', $restaurant1->id)->delete();
        Menu::where('restaurant_id', $restaurant2->id)->delete();
        Menu::where('restaurant_id', $restaurant3->id)->delete();
        Menu::where('restaurant_id', $restaurant4->id)->delete();

        // Menu untuk Warung Padang
        Menu::create(['restaurant_id' => $restaurant1->id, 'name' => 'Rendang Sapi', 'description' => 'Rendang sapi empuk dengan bumbu khas Padang', 'price' => 35000]);
        Menu::create(['restaurant_id' => $restaurant1->id, 'name' => 'Ayam Pop', 'description' => 'Ayam goreng khas Padang yang gurih', 'price' => 25000]);
        Menu::create(['restaurant_id' => $restaurant1->id, 'name' => 'Gulai Ikan', 'description' => 'Ikan kakap merah dengan gulai kuning', 'price' => 30000]);
        Menu::create(['restaurant_id' => $restaurant1->id, 'name' => 'Perkedel Kentang', 'description' => 'Perkedel kentang renyah', 'price' => 8000]);
        Menu::create(['restaurant_id' => $restaurant1->id, 'name' => 'Es Teh Manis', 'description' => 'Teh manis dingin segar', 'price' => 5000]);

        // Menu untuk Sushi Zen
        Menu::create(['restaurant_id' => $restaurant2->id, 'name' => 'Salmon Sushi Set', 'description' => '8 pcs salmon sushi fresh', 'price' => 85000]);
        Menu::create(['restaurant_id' => $restaurant2->id, 'name' => 'California Roll', 'description' => 'California roll dengan crab stick', 'price' => 65000]);
        Menu::create(['restaurant_id' => $restaurant2->id, 'name' => 'Tuna Sashimi', 'description' => '10 slices tuna sashimi premium', 'price' => 120000]);
        Menu::create(['restaurant_id' => $restaurant2->id, 'name' => 'Ramen Shoyu', 'description' => 'Ramen kuah shoyu dengan chashu', 'price' => 55000]);
        Menu::create(['restaurant_id' => $restaurant2->id, 'name' => 'Green Tea', 'description' => 'Teh hijau Jepang hangat', 'price' => 15000]);

        // Menu untuk Bebek Goreng
        Menu::create(['restaurant_id' => $restaurant3->id, 'name' => 'Bebek Goreng', 'description' => 'Bebek goreng crispy dengan sambal', 'price' => 45000]);
        Menu::create(['restaurant_id' => $restaurant3->id, 'name' => 'Bebek Bakar', 'description' => 'Bebek bakar bumbu kecap pedas', 'price' => 48000]);
        Menu::create(['restaurant_id' => $restaurant3->id, 'name' => 'Ayam Goreng', 'description' => 'Ayam goreng kampung renyah', 'price' => 28000]);
        Menu::create(['restaurant_id' => $restaurant3->id, 'name' => 'Nasi Goreng Bebek', 'description' => 'Nasi goreng dengan suwiran bebek', 'price' => 35000]);
        Menu::create(['restaurant_id' => $restaurant3->id, 'name' => 'Es Kelapa Muda', 'description' => 'Kelapa muda segar', 'price' => 12000]);

        // Menu untuk Pizza Italia
        Menu::create(['restaurant_id' => $restaurant4->id, 'name' => 'Margherita Pizza', 'description' => 'Pizza klasik dengan mozzarella dan basil', 'price' => 75000]);
        Menu::create(['restaurant_id' => $restaurant4->id, 'name' => 'Pepperoni Pizza', 'description' => 'Pizza dengan pepperoni dan keju melimpah', 'price' => 85000]);
        Menu::create(['restaurant_id' => $restaurant4->id, 'name' => 'Quattro Formaggi', 'description' => 'Pizza 4 keju premium', 'price' => 95000]);
        Menu::create(['restaurant_id' => $restaurant4->id, 'name' => 'Caesar Salad', 'description' => 'Salad segar dengan dressing Caesar', 'price' => 45000]);
        Menu::create(['restaurant_id' => $restaurant4->id, 'name' => 'Tiramisu', 'description' => 'Dessert tiramisu authentic', 'price' => 35000]);

        // ============================================
        // 4. CREATE RESERVATIONS & ORDERS
        // ============================================
        
        Reservation::whereIn('customer_id', [$customer1->id, $customer2->id, $customer3->id])->delete();

        $menus = Menu::all()->keyBy('name');

        // Reservation 1: Customer Budi - Confirmed (Hari ini)
        $reservation1 = Reservation::create([
            'customer_id' => $customer1->id,
            'restaurant_id' => $restaurant1->id,
            'reservation_date' => Carbon::today()->addHours(18),
            'guest_count' => 4,
            'table_number' => 'A3',
            'status' => 'confirmed',
            'special_request' => 'Tolong sediakan kursi bayi',
        ]);

        $order1 = Order::create([
            'customer_id' => $customer1->id,
            'restaurant_id' => $restaurant1->id,
            'reservation_id' => $reservation1->id,
            'order_type' => 'pre_order',
            'total' => 103000,
            'status' => 'confirmed',
            'preparation_time' => 30,
        ]);

        $order1->orderItems()->createMany([
            ['menu_id' => $menus['Rendang Sapi']->id, 'menu_name' => 'Rendang Sapi', 'quantity' => 2, 'price' => 35000],
            ['menu_id' => $menus['Ayam Pop']->id, 'menu_name' => 'Ayam Pop', 'quantity' => 1, 'price' => 25000],
            ['menu_id' => $menus['Es Teh Manis']->id, 'menu_name' => 'Es Teh Manis', 'quantity' => 4, 'price' => 5000],
        ]);

        // Reservation 2: Customer Siti - Pending (Besok)
        $reservation2 = Reservation::create([
            'customer_id' => $customer2->id,
            'restaurant_id' => $restaurant2->id,
            'reservation_date' => Carbon::tomorrow()->addHours(19),
            'guest_count' => 2,
            'status' => 'pending',
            'special_request' => 'Meja di area non-smoking',
        ]);

        $order2 = Order::create([
            'customer_id' => $customer2->id,
            'restaurant_id' => $restaurant2->id,
            'reservation_id' => $reservation2->id,
            'order_type' => 'pre_order',
            'total' => 235000,
            'status' => 'pending',
            'preparation_time' => 45,
        ]);

        $order2->orderItems()->createMany([
            ['menu_id' => $menus['Salmon Sushi Set']->id, 'menu_name' => 'Salmon Sushi Set', 'quantity' => 1, 'price' => 85000],
            ['menu_id' => $menus['Tuna Sashimi']->id, 'menu_name' => 'Tuna Sashimi', 'quantity' => 1, 'price' => 120000],
            ['menu_id' => $menus['Green Tea']->id, 'menu_name' => 'Green Tea', 'quantity' => 2, 'price' => 15000],
        ]);

        // Reservation 3: Customer Andi - Completed (Kemarin)
        $reservation3 = Reservation::create([
            'customer_id' => $customer3->id,
            'restaurant_id' => $restaurant4->id,
            'reservation_date' => Carbon::yesterday()->addHours(20),
            'guest_count' => 3,
            'table_number' => 'B1',
            'status' => 'completed',
        ]);

        $order3 = Order::create([
            'customer_id' => $customer3->id,
            'restaurant_id' => $restaurant4->id,
            'reservation_id' => $reservation3->id,
            'order_type' => 'pre_order',
            'total' => 205000,
            'status' => 'completed',
            'preparation_time' => 30,
        ]);

        $order3->orderItems()->createMany([
            ['menu_id' => $menus['Margherita Pizza']->id, 'menu_name' => 'Margherita Pizza', 'quantity' => 1, 'price' => 75000],
            ['menu_id' => $menus['Quattro Formaggi']->id, 'menu_name' => 'Quattro Formaggi', 'quantity' => 1, 'price' => 95000],
            ['menu_id' => $menus['Tiramisu']->id, 'menu_name' => 'Tiramisu', 'quantity' => 1, 'price' => 35000],
        ]);

        $this->command->info("\n✅ Seeder berhasil dijalankan!");
        $this->command->info("📊 Data yang dibuat:");
        $this->command->info("   - Users: 4 (1 Admin, 3 Customer)");
        $this->command->info("   - Restaurants: 4 (dengan gambar real dari Unsplash)");
        $this->command->info("   - Menus: 20");
        $this->command->info("   - Reservations: 3");
        $this->command->info("   - Orders: 3");
        $this->command->info("\n🔐 Login Credentials:");
        $this->command->info("   Admin: username=admin, password=admin123");
        $this->command->info("   Customer 1: username=budi, password=password123");
        $this->command->info("   Customer 2: username=siti, password=password123");
        $this->command->info("   Customer 3: username=andi, password=password123\n");
    }
}