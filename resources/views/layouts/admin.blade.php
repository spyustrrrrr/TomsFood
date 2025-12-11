{{-- 
    File: resources/views/layouts/admin.blade.php
    Admin Layout dengan Tailwind CSS (CDN Version)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Toman Food</title>
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'orange': {
                            500: '#ff6b35',
                            600: '#ff6b35',
                            700: '#e55a2b',
                        }
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex-shrink-0">
            <div class="p-6">
                {{-- Logo --}}
                <h2 class="text-2xl font-bold text-orange-500 mb-8">
                    ⚙️ Admin Panel
                </h2>

                {{-- Navigation --}}
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        <span>🏠</span>
                        <span>Dashboard</span>
                    </a>

                    <div class="border-t border-gray-700 my-4"></div>

                    <a href="{{ route('admin.reservations.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.reservations.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        <span>📅</span>
                        <span>Reservations</span>
                    </a>

                    <a href="{{ route('admin.restaurants.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.restaurants.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        <span>🏪</span>
                        <span>Restaurants</span>
                    </a>

                    <a href="{{ route('admin.menus.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.menus.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        <span>📋</span>
                        <span>Menus</span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        <span>🧾</span>
                        <span>Orders</span>
                    </a>

                    <div class="border-t border-gray-700 my-4"></div>

                    <a href="{{ route('home') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700">
                        <span>◀️</span>
                        <span>Back to Site</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-red-400 hover:bg-gray-700 w-full text-left">
                            <span>🚪</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top Bar --}}
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-800">
                        @yield('title', 'Dashboard')
                    </h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">
                            👤 {{ auth()->user()->full_name }}
                        </span>
                    </div>
                </div>
            </header>

            {{-- Content Area --}}
            <main class="flex-1 overflow-y-auto bg-gray-100 p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>