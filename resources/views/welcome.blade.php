<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Coffee Kane - Sistem Pemesanan') }}</title>

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-6zjz6QHZv4+xLgG8X4rZRA/jJojps9vSyZZH9GQGVPRT8MWflO7NjMlOr+XB+KUXQWLOgKrbDU37EhmnHlx1hg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tambahan Global Style - Emerald & Indigo Theme --}}
    <style>
        .glow-text {
            text-shadow: 0 0 6px rgba(16, 185, 129, 0.6);
            color: #6ee7b7;
        }
        .glow-text-indigo {
            text-shadow: 0 0 6px rgba(99, 102, 241, 0.6);
            color: #a5b4fc;
        }
        @keyframes fadeSlideUp {
            0% { opacity: 0; transform: translateY(30px) scale(0.98); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 5px rgba(16, 185, 129, 0.4); }
            50% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.8); }
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-fadeSlide {
            animation: fadeSlideUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        .animate-gradient {
            animation: gradientShift 8s ease infinite;
            background-size: 200% 200%;
        }
        .card-glow:hover {
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4), 0 0 8px rgba(16, 185, 129, 0.6);
            transform: translateY(-4px) scale(1.01);
            transition: all 0.3s ease;
        }
        .gradient-border {
            background: linear-gradient(135deg, #10b981, #3b82f6, #6366f1);
            padding: 2px;
            border-radius: 12px;
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
        .floating-coffee {
            position: absolute;
            opacity: 0.1;
            font-size: 8rem;
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-950 text-white min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-gray-950 border-b border-gray-800 sticky top-0 z-50 backdrop-blur-md bg-gray-950/95">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                {{-- Logo --}}
                <div class="flex items-center space-x-3">
                    <div class="text-2xl text-emerald-400 animate-float">
                        <i class="fas fa-mug-hot"></i>
                    </div>
                    <h1 class="text-xl font-bold glow-text">
                        COFFEE KANE
                    </h1>
                </div>

                {{-- Navigation Menu --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="text-gray-300 hover:text-emerald-400 transition font-medium relative group">
                        Home
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-400 transition-all group-hover:w-full"></span>
                    </a>

                    {{-- Dropdown Kategori --}}
                    <div class="relative group">
                        <button class="text-gray-300 hover:text-emerald-400 transition font-medium flex items-center space-x-1">
                            <span>Kategori</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-300 group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute top-full left-0 mt-2 w-48 bg-gray-900 rounded-lg shadow-xl py-2 hidden group-hover:block border border-gray-700">
                            <a href="{{ url('/') }}" class="block px-4 py-2 text-gray-300 hover:bg-emerald-600 transition text-sm font-medium border-b border-gray-700">
                                <i class="fas fa-th-large mr-3 text-emerald-400"></i>Semua Kategori
                            </a>
                            @foreach($categories->unique('name') as $category)
                                <a href="{{ url('/?category=' . $category->id) }}" class="block px-4 py-2 text-gray-300 hover:bg-emerald-600 transition text-sm font-medium">
                                    <i class="fas fa-tag mr-3 text-emerald-400"></i>{{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('promo') }}" class="text-gray-300 hover:text-emerald-400 transition font-medium relative group">
                        Promo
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-400 transition-all group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('about') }}" class="text-gray-300 hover:text-emerald-400 transition font-medium relative group">
                        About
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-400 transition-all group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('reservasi') }}" class="text-gray-300 hover:text-emerald-400 transition font-medium relative group">
                        Reservasi
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-400 transition-all group-hover:w-full"></span>
                    </a>
                </div>

                {{-- Auth Buttons --}}
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-6 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-6 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </a>
                        <a href="{{ route('register') }}" 
                           class="border border-emerald-500 text-emerald-400 hover:bg-emerald-500 hover:text-white px-6 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 font-medium">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative py-20 px-6 overflow-hidden hero-gradient animate-gradient">
        {{-- Floating Coffee Icons --}}
        <div class="floating-coffee" style="top: 10%; left: 10%; animation-delay: 0s;">
            <i class="fas fa-mug-hot"></i>
        </div>
        <div class="floating-coffee" style="top: 20%; right: 15%; animation-delay: 1s;">
            <i class="fas fa-coffee"></i>
        </div>
        <div class="floating-coffee" style="bottom: 15%; left: 20%; animation-delay: 2s;">
            <i class="fas fa-beer-mug-empty"></i>
        </div>
        <div class="floating-coffee" style="bottom: 25%; right: 10%; animation-delay: 3s;">
            <i class="fas fa-mug-saucer"></i>
        </div>
        
        <div class="absolute inset-0 coffee-pattern"></div>
        <div class="max-w-6xl mx-auto text-center relative z-10">
            <div class="animate-fadeSlide" style="animation-delay: 0.2s">
                <h2 class="text-5xl md:text-6xl font-black mb-6 leading-tight">
                    Selamat Datang di<br>
                    <span class="glow-text">Coffee Kane</span>
                </h2>
            </div>
            <div class="animate-fadeSlide" style="animation-delay: 0.4s">
                <p class="text-xl text-gray-100 leading-relaxed max-w-3xl mx-auto mb-8">
                    Rasakan kehangatan dalam setiap cangkir kopi terbaik dari biji pilihan lokal. 
                    Pengalaman rasa yang tak terlupakan di setiap tegukan.
                </p>
            </div>
            <div class="animate-fadeSlide" style="animation-delay: 0.6s">
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="#menu" 
                       class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg border-2 border-emerald-400/50">
                        <i class="fas fa-coffee mr-2"></i>Lihat Menu
                    </a>
                    <a href="{{ route('reservasi') }}"
                       class="bg-white/10 backdrop-blur-sm border-2 border-white/20 text-white hover:bg-white/20 hover:border-white/30 px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-calendar-plus mr-2"></i>Reservasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-16 px-6 bg-gray-900">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center animate-fadeSlide" style="animation-delay: 0.2s">
                    <div class="bg-emerald-500/20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse-glow">
                        <i class="fas fa-seedling text-2xl text-emerald-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-white">Biji Pilihan</h3>
                    <p class="text-gray-400">Kopi berkualitas dari biji lokal terbaik Indonesia</p>
                </div>
                <div class="text-center animate-fadeSlide" style="animation-delay: 0.4s">
                    <div class="bg-emerald-500/20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse-glow">
                        <i class="fas fa-mug-hot text-2xl text-emerald-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-white">Rasa Autentik</h3>
                    <p class="text-gray-400">Cita rasa kopi yang otentik dan nikmat</p>
                </div>
                <div class="text-center animate-fadeSlide" style="animation-delay: 0.6s">
                    <div class="bg-emerald-500/20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse-glow">
                        <i class="fas fa-heart text-2xl text-emerald-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-white">Dibuat dengan Cinta</h3>
                    <p class="text-gray-400">Setiap cangkir dibuat dengan penuh passion</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Products Section --}}
    <section id="menu" class="py-16 px-6 bg-gray-950">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12 animate-fadeSlide">
                <h3 class="text-4xl font-bold mb-4 glow-text">
                    Menu Favorit Kami
                </h3>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                    Temukan berbagai pilihan kopi dan makanan lezat yang siap memanjakan lidah Anda
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $index => $product)
                    <div class="gradient-border animate-fadeSlide" style="animation-delay: {{ $index * 0.1 }}s">
                        <div class="bg-gray-900 rounded-xl p-4 h-full flex flex-col">
                            {{-- Gambar Produk --}}
                            <div class="bg-gray-800 rounded-lg h-40 mb-4 flex items-center justify-center overflow-hidden relative">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="h-full w-full object-cover transition-transform duration-500 hover:scale-110">
                                @else
                                    <div class="text-emerald-400 text-center">
                                        <i class="fas fa-mug-hot text-4xl mb-2"></i>
                                        <p class="text-sm text-gray-400">No Image</p>
                                    </div>
                                @endif
                                @if($product->stock <= 0)
                                    <div class="absolute inset-0 bg-red-500/20 rounded-lg flex items-center justify-center">
                                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            Stok Habis
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info Produk --}}
                            <div class="flex-grow">
                                <h4 class="font-bold text-white mb-2 text-lg">{{ $product->name }}</h4>
                                <p class="text-2xl font-black text-emerald-400 mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-sm mb-4 {{ $product->stock > 0 ? 'text-gray-400' : 'text-red-400 font-semibold' }}">
                                    <i class="fas fa-box mr-1"></i>
                                    {{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Stok Habis' }}
                                </p>
                            </div>

                            {{-- Tombol Action --}}
                            @auth
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-shopping-cart mr-2"></i> 
                                        {{ $product->stock <= 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                   class="w-full bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white py-3 rounded-lg font-semibold text-center block transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Pesan
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 animate-fadeSlide">
                        <i class="fas fa-mug-hot text-6xl text-gray-600 mb-4"></i>
                        <p class="text-gray-400 text-xl">Tidak ada produk tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- CTA Sections --}}
    <section class="py-16 px-6 bg-gray-900">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Reservasi Card --}}
                <div class="gradient-border animate-fadeSlide">
                    <div class="bg-gray-800 rounded-xl p-8 text-center h-full">
                        <div class="bg-emerald-500/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-check text-2xl text-emerald-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Reservasi Meja</h3>
                        <p class="text-gray-400 mb-6">Ingin menikmati kopi bersama orang terkasih? Buat reservasi sekarang!</p>
                        <a href="{{ route('reservasi') }}"
                           class="inline-block bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-calendar-plus mr-2"></i> Buat Reservasi
                        </a>
                    </div>
                </div>

                {{-- Promo Card --}}
                <div class="gradient-border animate-fadeSlide" style="animation-delay: 0.2s">
                    <div class="bg-gray-800 rounded-xl p-8 text-center h-full">
                        <div class="bg-indigo-500/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-percentage text-2xl text-indigo-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Promo Spesial</h3>
                        <p class="text-gray-400 mb-6">Dapatkan penawaran menarik dan diskon spesial untuk pembelian produk tertentu.</p>
                        <a href="{{ route('promo') }}"
                           class="inline-block bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-tags mr-2"></i> Lihat Promo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-950 border-t border-gray-800 py-12">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="animate-fadeSlide">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="text-2xl text-emerald-400">
                            <i class="fas fa-mug-hot"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white">COFFEE KANE</h3>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Tempat terbaik untuk menikmati kopi berkualitas dengan suasana yang hangat dan nyaman.
                    </p>
                </div>

                <div class="animate-fadeSlide" style="animation-delay: 0.1s">
                    <h4 class="text-white font-semibold mb-4 text-lg">Follow Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition transform hover:scale-110 text-xl">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition transform hover:scale-110 text-xl">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition transform hover:scale-110 text-xl">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition transform hover:scale-110 text-xl">
                            <i class="fab fa-spotify"></i>
                        </a>
                    </div>
                </div>

                <div class="animate-fadeSlide" style="animation-delay: 0.2s">
                    <h4 class="text-white font-semibold mb-4 text-lg">Kontak</h4>
                    <div class="space-y-2 text-sm">
                        <p class="flex items-center space-x-2">
                            <i class="fas fa-phone text-emerald-400"></i>
                            <span class="text-gray-300">+62 812-3456-7890</span>
                        </p>
                        <p class="flex items-center space-x-2">
                            <i class="fas fa-envelope text-emerald-400"></i>
                            <span class="text-gray-300">hello@coffeekane.com</span>
                        </p>
                    </div>
                </div>

                <div class="animate-fadeSlide" style="animation-delay: 0.3s">
                    <h4 class="text-white font-semibold mb-4 text-lg">Lokasi</h4>
                    <div class="flex items-start space-x-2 text-sm">
                        <i class="fas fa-map-marker-alt text-emerald-400 mt-1"></i>
                        <div>
                            <p class="text-gray-300">Jl. Contoh Alamat No. 123</p>
                            <p class="text-gray-300">Jakarta Selatan, Indonesia</p>
                            <p class="text-gray-300">11880</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center animate-fadeSlide">
                <div class="flex justify-center items-center space-x-6 mb-4">
                    <a href="#" class="text-gray-400 hover:text-emerald-400 transition transform hover:scale-110">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-emerald-400 transition transform hover:scale-110">
                        <i class="fab fa-tiktok text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-emerald-400 transition transform hover:scale-110">
                        <i class="fab fa-youtube text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-emerald-400 transition transform hover:scale-110">
                        <i class="fab fa-spotify text-lg"></i>
                    </a>
                </div>
                <p class="text-sm text-gray-400">
                    &copy; {{ date('Y') }} Coffee Kane • Dibuat dengan <i class="fas fa-heart text-emerald-400 mx-1"></i> untuk pecinta kopi
                </p>
            </div>
        </div>
    </footer>

</body>
</html>