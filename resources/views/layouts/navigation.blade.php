<header class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <div class="text-xl font-bold text-indigo-700">
        Coffee Kane
    </div>

    <nav class="flex space-x-6 text-sm">
        @auth
            {{-- Admin --}}
            @if(auth()->user()->hasRole('admin'))
                <x-nav-item route="admin.dashboard" label="Dashboard" />
                <x-nav-item route="admin.users.index" label="User" />
                <x-nav-item route="admin.roles.index" label="Role" />
                <x-nav-item route="admin.products.index" label="Produk" />
                <x-nav-item route="admin.promos.index" label="Promo" />
                <x-nav-item route="admin.transactions.index" label="Transaksi" />
                <x-nav-item route="admin.reports.index" label="Laporan" />
            @endif

            {{-- Kasir --}}
            @if(auth()->user()->hasRole('kasir'))
                <x-nav-item route="kasir.dashboard" label="Dashboard" />
                <x-nav-item route="kasir.transactions.index" label="Transaksi" />
            @endif

            {{-- Manager --}}
            @if(auth()->user()->hasRole('manager'))
                <x-nav-item route="manager.dashboard" label="Dashboard" />
                <x-nav-item route="manager.reports.index" label="Laporan" />
            @endif

            {{-- Customer --}}
            @if(auth()->user()->hasRole('customer'))
                <x-nav-item route="customer.dashboard" label="Dashboard" />
                <x-nav-item route="customer.transactions.history" label="Pesanan" />
                <x-nav-item route="products.index" label="Bundling" />
            @endif

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-red-600 hover:text-red-800 font-medium ml-6">Logout</button>
            </form>
        @endauth
    </nav>
</header>
