<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Toman Food')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold text-orange-500">Admin Panel</h2>
                <p class="text-sm text-gray-400">Toman Food</p>
            </div>
            
            <nav class="mt-8">
                <a href="{{ route('admin.dashboard') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.restaurants.index') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.restaurants.*') ? 'bg-gray-700' : '' }}">
                    🏪 Restaurants
                </a>
                <a href="{{ route('admin.menus.index') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.menus.*') ? 'bg-gray-700' : '' }}">
                    🍔 Menus
                </a>
                <a href="{{ route('admin.orders.index') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">
                    📦 Orders
                </a>
                <hr class="my-4 border-gray-700">
                <a href="{{ route('home') }}" class="block px-4 py-3 hover:bg-gray-700">
                    🏠 Back to Website
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-700 text-red-400">
                        🚪 Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    <div class="text-gray-600">
                        Welcome, <span class="font-semibold">{{ auth()->user()->first_name }}</span>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-6 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>