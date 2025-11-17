<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Coffee Kane - Sistem Pemesanan') }}</title>

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    {{-- CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Modern Global Style - Emerald & Indigo Theme --}}
    <style>
        .glow-text {
            text-shadow: 0 0 10px rgba(16, 185, 129, 0.6);
            color: #6ee7b7;
        }
        .glow-text-indigo {
            text-shadow: 0 0 10px rgba(99, 102, 241, 0.6);
            color: #a5b4fc;
        }
        @keyframes fadeSlideUp {
            0% { 
                opacity: 0; 
                transform: translateY(20px) scale(0.98); 
            }
            100% { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }
        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
            }
            50% { 
                transform: translateY(-8px) rotate(2deg); 
            }
        }
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        @keyframes slideInFromLeft {
            0% { 
                opacity: 0; 
                transform: translateX(-100px); 
            }
            100% { 
                opacity: 1; 
                transform: translateX(0); 
            }
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .animate-fadeSlide {
            animation: fadeSlideUp 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
            opacity: 0;
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        .animate-shimmer {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            background-size: 1000px 100%;
        }
        .animate-slideInLeft {
            animation: slideInFromLeft 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }
        .animate-gradient {
            animation: gradientShift 8s ease infinite;
            background-size: 200% 200%;
        }

        .glass-effect {
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .gradient-border {
            position: relative;
            background: linear-gradient(135deg, #10b981, #3b82f6, #6366f1);
            padding: 2px;
            border-radius: 16px;
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            padding: 2px;
            background: linear-gradient(135deg, #10b981, #3b82f6, #6366f1);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: xor;
            -webkit-mask-composite: xor;
        }

        .sidebar-hover-effect {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-hover-effect:hover {
            transform: translateX(8px);
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.1), transparent);
        }

        .notification-pulse::before {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background: #3b82f6;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .bg-grid-pattern {
            background-image: 
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0f172a, #1e1b4b, #164e63, #1e3a8a);
        }

        .coffee-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.05) 0%, transparent 50%);
            background-size: 50% 50%, 50% 50%, 100% 100%;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-900 via-gray-950 to-black text-white min-h-screen flex overflow-x-hidden">

    {{-- Enhanced Sidebar --}}
    @auth
    <aside class="w-20 lg:w-64 glass-effect h-screen flex flex-col py-8 sticky top-0 border-r border-gray-800/50 animate-slideInLeft">
        {{-- Logo --}}
        <div class="px-4 mb-12">
            <div class="flex items-center space-x-3">
                <div class="text-2xl text-emerald-400 animate-float">
                    <i class="fas fa-mug-hot"></i>
                </div>
                <h1 class="text-xl font-bold glow-text hidden lg:block">
                    COFFEE KANE
                </h1>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 flex flex-col space-y-2 px-4">
            @php $role = auth()->user()->role; @endphp

            {{-- ADMIN --}}
            @if($role === 'admin')
                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="<i class='fas fa-chart-line'></i>" label="Dashboard" />
                <x-sidebar-link href="{{ route('admin.products.index') }}" icon="<i class='fas fa-box'></i>" label="Kelola Produk" />
                <x-sidebar-link href="{{ route('admin.users.index') }}" icon="<i class='fas fa-users'></i>" label="Kelola User" />
                <x-sidebar-link href="{{ route('admin.roles.index') }}" icon="<i class='fas fa-shield-alt'></i>" label="Kelola Role" />
                <x-sidebar-link href="{{ route('admin.promos.index') }}" icon="<i class='fas fa-gift'></i>" label="Kelola Promo" />
                <x-sidebar-link href="{{ route('admin.transactions.index') }}" icon="<i class='fas fa-receipt'></i>" label="Kelola Transaksi" />
                <x-sidebar-link href="{{ route('admin.reports.index') }}" icon="<i class='fas fa-chart-bar'></i>" label="Laporan" />

            {{-- KASIR --}}
            @elseif($role === 'kasir')
                <x-sidebar-link href="{{ route('kasir.dashboard') }}" icon="<i class='fas fa-chart-line'></i>" label="Dashboard" />
                <x-sidebar-link href="{{ route('kasir.transactions.create') }}" icon="<i class='fas fa-shopping-cart'></i>" label="Transaksi" />
                <x-sidebar-link href="{{ route('kasir.transactions.index') }}" icon="<i class='fas fa-history'></i>" label="Riwayat" />
                <x-sidebar-link href="{{ route('kasir.reports.index') }}" icon="<i class='fas fa-file-alt'></i>" label="Laporan" />

            {{-- CUSTOMER --}}
            @elseif($role === 'customer')
                <x-sidebar-link href="{{ route('customer.dashboard') }}" icon="<i class='fas fa-home'></i>" label="Dashboard" />
                <x-sidebar-link href="{{ route('customer.transactions.history') }}" icon="<i class='fas fa-receipt'></i>" label="Pesanan" />
                <x-sidebar-link href="{{ route('customer.promos.index') }}" icon="<i class='fas fa-tags'></i>" label="Promo" />
                <x-sidebar-link href="{{ route('about') }}" icon="<i class='fas fa-info-circle'></i>" label="Tentang" />
                <x-sidebar-link href="{{ route('customer.reservation.index') }}" icon="<i class='fas fa-calendar-alt'></i>" label="Reservasi" />

            {{-- MANAGER --}}
            @elseif($role === 'manager')
                <x-sidebar-link href="{{ route('manager.dashboard') }}" icon="<i class='fas fa-chart-line'></i>" label="Dashboard" />
                <x-sidebar-link href="{{ route('manager.reports.index') }}" icon="<i class='fas fa-chart-bar'></i>" label="Laporan" />
            @endif
        </nav>

        {{-- User Profile & Logout --}}
        <div class="px-4 mt-auto space-y-4">
            <div class="border-t border-gray-700/50 pt-4">
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 text-gray-300 hover:text-emerald-400 transition group sidebar-hover-effect p-2 rounded-lg">
                    <img src="https://i.pravatar.cc/40?u={{ auth()->user()->email }}" 
                         class="w-8 h-8 rounded-full ring-2 ring-emerald-400/50 group-hover:ring-emerald-400 transition" />
                    <div class="hidden lg:block">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </a>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 text-red-400 hover:text-red-300 transition group sidebar-hover-effect p-2 rounded-lg">
                    <i class="fas fa-power-off text-lg"></i>
                    <span class="hidden lg:block text-sm font-medium">Logout</span>
                </button>
            </form>
        </div>
    </aside>
    @endauth

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col min-h-screen bg-grid-pattern">

        {{-- Enhanced Navbar --}}
        <header class="glass-effect border-b border-gray-800/50 sticky top-0 z-40">
            <div class="px-6 py-4 flex justify-between items-center">
                {{-- Breadcrumb & Title --}}
                <div class="flex items-center space-x-4">
                    <button onclick="window.history.back()" 
                            class="p-2 rounded-lg bg-gray-800/50 hover:bg-emerald-500/20 text-gray-400 hover:text-emerald-400 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </button>
                    
                    <div class="flex items-center space-x-3">
                        <h1 class="text-2xl font-bold glow-text animate-fadeSlide">
                            @yield('title', 'Dashboard')
                        </h1>
                        @hasSection('subtitle')
                        <span class="text-gray-400 text-lg">/</span>
                        <span class="text-gray-300 text-lg">@yield('subtitle')</span>
                        @endif
                    </div>
                </div>

                {{-- Right Side Actions --}}
                <div class="flex items-center space-x-6">
                    {{-- Notifications --}}
                    <div class="relative group">
                        <button class="relative p-2 rounded-lg bg-gray-800/50 hover:bg-emerald-500/20 text-gray-400 hover:text-emerald-400 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="notification-pulse"></span>
                        </button>
                        
                        {{-- Notification Dropdown --}}
                        <div class="absolute right-0 mt-2 w-80 glass-effect rounded-xl shadow-2xl hidden group-hover:block animate-fadeSlide border border-gray-700/50">
                            <div class="p-4 border-b border-gray-700/50">
                                <h3 class="font-semibold text-white flex items-center space-x-2">
                                    <i class="fas fa-bell text-emerald-400"></i>
                                    <span>Notifikasi</span>
                                    <span class="bg-emerald-500 text-white text-xs px-2 py-1 rounded-full">3 baru</span>
                                </h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <a href="#" class="flex items-start space-x-3 p-4 hover:bg-gray-800/50 transition border-b border-gray-700/30 last:border-0">
                                    <div class="bg-green-500/20 p-2 rounded-lg">
                                        <i class="fas fa-check text-green-400 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-white">Transaksi #001 berhasil</p>
                                        <p class="text-xs text-gray-400 mt-1">2 menit yang lalu</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start space-x-3 p-4 hover:bg-gray-800/50 transition border-b border-gray-700/30 last:border-0">
                                    <div class="bg-blue-500/20 p-2 rounded-lg">
                                        <i class="fas fa-tags text-blue-400 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-white">Promo baru tersedia</p>
                                        <p class="text-xs text-gray-400 mt-1">1 jam yang lalu</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start space-x-3 p-4 hover:bg-gray-800/50 transition">
                                    <div class="bg-amber-500/20 p-2 rounded-lg">
                                        <i class="fas fa-star text-amber-400 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-white">Review pelanggan masuk</p>
                                        <p class="text-xs text-gray-400 mt-1">3 jam yang lalu</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="flex items-center space-x-2">
                        <button class="p-2 rounded-lg bg-gray-800/50 hover:bg-emerald-500/20 text-gray-400 hover:text-emerald-400 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-cog text-lg"></i>
                        </button>
                        <button class="p-2 rounded-lg bg-gray-800/50 hover:bg-emerald-500/20 text-gray-400 hover:text-emerald-400 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-question text-lg"></i>
                        </button>
                    </div>

                    {{-- User Profile --}}
                    <div class="relative group">
                        <button class="flex items-center space-x-3 p-2 rounded-lg bg-gray-800/50 hover:bg-emerald-500/20 transition-all duration-300">
                            <img src="https://i.pravatar.cc/40?u={{ auth()->user()->email }}" 
                                 class="w-8 h-8 rounded-full ring-2 ring-emerald-400/50 group-hover:ring-emerald-400 transition" />
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-300 group-hover:rotate-180"></i>
                        </button>

                        {{-- Profile Dropdown --}}
                        <div class="absolute right-0 mt-2 w-56 glass-effect rounded-xl shadow-2xl hidden group-hover:block animate-fadeSlide border border-gray-700/50">
                            <div class="p-4 border-b border-gray-700/50">
                                <div class="flex items-center space-x-3">
                                    <img src="https://i.pravatar.cc/48?u={{ auth()->user()->email }}" 
                                         class="w-10 h-10 rounded-full ring-2 ring-emerald-400" />
                                    <div>
                                        <p class="font-semibold text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-sm text-gray-400">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-300 hover:bg-emerald-500/20 hover:text-emerald-400 transition">
                                    <i class="fas fa-user-edit w-5"></i>
                                    <span>Edit Profil</span>
                                </a>
                                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-300 hover:bg-emerald-500/20 hover:text-emerald-400 transition">
                                    <i class="fas fa-cog w-5"></i>
                                    <span>Pengaturan</span>
                                </a>
                                <div class="border-t border-gray-700/50 mt-2 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg text-red-400 hover:bg-red-500/20 hover:text-red-300 transition">
                                            <i class="fas fa-power-off w-5"></i>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Header --}}
        @hasSection('header')
        <div class="border-b border-gray-800/50 bg-gradient-to-r from-gray-900/50 to-transparent">
            <div class="px-6 py-6">
                <div class="max-w-7xl mx-auto">
                    @yield('header')
                </div>
            </div>
        </div>
        @endif

        {{-- Main Content --}}
        <main class="flex-1 w-full p-6 animate-fadeSlide" style="animation-delay: 0.1s">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="glass-effect border-t border-gray-800/50 py-4 px-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
                <p class="text-sm text-gray-400">
                    &copy; {{ date('Y') }} Coffee Kane. All rights reserved.
                </p>
                <div class="flex items-center space-x-4 text-sm text-gray-400">
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-heart text-emerald-400"></i>
                        <span>Made with passion</span>
                    </span>
                    <span>•</span>
                    <span>v1.0.0</span>
                </div>
            </div>
        </footer>
    </div>

    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>