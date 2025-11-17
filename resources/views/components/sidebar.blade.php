<aside class="w-20 md:w-44 bg-gray-900 h-screen flex flex-col items-center py-6 text-white sticky top-0">
    <h1 class="text-xl font-bold mb-10">COFFE KANE</h1>

    <nav class="flex flex-col space-y-6 w-full px-3">

        {{-- Admin --}}
        @if(auth()->user()->role === 'admin')
            <x-sidebar-link href="{{ route('admin.products.index') }}" icon="📦" label="Kelola Produk" />
            <x-sidebar-link href="{{ route('admin.users.index') }}" icon="👥" label="Kelola User" />
            <x-sidebar-link href="{{ route('admin.roles.index') }}" icon="🛡️" label="Kelola Role" />
            <x-sidebar-link href="{{ route('admin.promos.index') }}" icon="🎁" label="Kelola Promo" />
            <x-sidebar-link href="{{ route('admin.transactions.index') }}" icon="🧾" label="Kelola Transaksi" />
            <x-sidebar-link href="{{ route('admin.reports.index') }}" icon="📈" label="Laporan Penjualan" />
        @endif

        {{-- Kasir --}}
        @if(auth()->user()->role === 'kasir')
            <x-sidebar-link href="{{ route('kasir.reports.index') }}" icon="📊" label="Report" />
            <x-sidebar-link href="{{ route('kasir.staff.index') }}" icon="👤" label="Staf" />
            <x-sidebar-link href="{{ route('kasir.attendance.index') }}" icon="📅" label="Absen" />
        @endif

        {{-- Customer --}}
        @if(auth()->user()->role === 'customer')
            <x-sidebar-link href="{{ route('customer.transactions.history') }}" icon="🧾" label="Order" />
            <x-sidebar-link href="{{ route('customer.promos.index') }}" icon="🎁" label="Promo" />
            <x-sidebar-link href="{{ route('about') }}" icon="ℹ️" label="About Us" />
            <x-sidebar-link href="{{ route('customer.reservation.index') }}" icon="📅" label="Reservation" />
        @endif

        {{-- Logout (semua role) --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="text-red-400 hover:text-red-500 flex flex-col items-center">
                <span class="text-xl">⏻</span>
                <span class="text-sm mt-1">Logout</span>
            </button>
        </form>
    </nav>
</aside>
