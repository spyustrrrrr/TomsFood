{{-- 
    File: resources/views/layouts/app.blade.php
    Main Layout dengan Tailwind CSS (CDN Version)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toman Food - Restaurant Reservation')</title>
    
    {{-- Tailwind CSS CDN (Quick Fix) --}}
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
<body class="bg-gray-50">
    {{-- Navbar --}}
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="text-2xl font-bold text-orange-600 hover:text-orange-700">
                    🍽️ Toman Food
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-orange-600 font-medium">
                        Home
                    </a>
                    <a href="{{ route('restaurants.index') }}" class="text-gray-700 hover:text-orange-600 font-medium">
                        Restaurants
                    </a>
                    
                    @auth
                        <a href="{{ route('reservations.index') }}" class="text-gray-700 hover:text-orange-600 font-medium">
                            My Reservations
                        </a>
                        
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-red-600 hover:text-red-700 font-medium">
                                Admin Panel
                            </a>
                        @endif

                        {{-- User Dropdown --}}
                        {{-- User Dropdown --}}
<div class="relative group">
    <button class="flex items-center space-x-2 text-gray-700 hover:text-orange-600 font-medium">
        <span>Hi, {{ auth()->user()->first_name }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div class="
        absolute right-0 mt-2 w-48
        bg-white rounded-md shadow-lg py-1 z-20
        opacity-0 invisible
        group-hover:opacity-100 group-hover:visible
        transition-all duration-200 delay-100
    ">
        <a href="{{ route('reservations.index') }}"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            📅 My Reservations
        </a>

        <hr class="my-1">

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                🚪 Logout
            </button>
        </form>
    </div>
</div>

                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-600 font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
                            Register
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button id="mobile-menu-button" class="md:hidden text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <a href="{{ route('home') }}" class="block py-2 text-gray-700 hover:text-orange-600">Home</a>
                <a href="{{ route('restaurants.index') }}" class="block py-2 text-gray-700 hover:text-orange-600">Restaurants</a>
                
                @auth
                    <a href="{{ route('reservations.index') }}" class="block py-2 text-gray-700 hover:text-orange-600">My Reservations</a>
                    
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 text-red-600 hover:text-red-700">Admin Panel</a>
                    @endif
                    
                    <hr class="my-2">
                    <span class="block py-2 text-gray-500">Hi, {{ auth()->user()->first_name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left py-2 text-red-600 hover:text-red-700">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-orange-600">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 text-orange-600 font-medium">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- About --}}
                <div>
                    <h3 class="text-xl font-bold text-orange-500 mb-4">🍽️ Toman Food</h3>
                    <p class="text-gray-400">
                        Platform reservasi restoran dengan pre-order menu. Mudah, cepat, dan praktis!
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-orange-500">Home</a></li>
                        <li><a href="{{ route('restaurants.index') }}" class="text-gray-400 hover:text-orange-500">Restaurants</a></li>
                        @auth
                            <li><a href="{{ route('reservations.index') }}" class="text-gray-400 hover:text-orange-500">My Reservations</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <p class="text-gray-400 mb-2">📧 foodtoman@gmail.com</p>
                    <p class="text-gray-400">📞 +62 877-0031-3085</p>
                </div>
            </div>

            <hr class="border-gray-800 my-6">

            <div class="text-center text-gray-500">
                <p>&copy; {{ date('Y') }} Toman Food. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- Mobile Menu Toggle Script --}}
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>

    @stack('scripts')
</body>
</html>