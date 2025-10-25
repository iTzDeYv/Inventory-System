@vite(['resources/css/app.css', 'resources/js/app.js'])


<nav x-data="{ open: false }" class="bg-gray-900 text-white shadow-lg">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
           <!-- Logo -->
           <div class="hidden space-x-8 sm:flex sm:ml-10">
                <div class="flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-2">
                        <span class="font-bold text-2xl tracking-wide">MHS AUTO SUPPLY</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                @if(Auth::check() && Auth::user()->type == 'user')
                    <a href="{{ route('user.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('dashboard') ? 'bg-gray-800' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('user.barcode.scanner') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('barcode.scanner') ? 'bg-gray-800' : '' }}">
                        Barcode Scan
                    </a>
                @elseif(Auth::check() && Auth::user()->type == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('dashboard') ? 'bg-gray-800' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.viewsupplier') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.viewsupplier') ? 'bg-gray-800' : '' }}">
                        View Supplier
                    </a>
                    <a href="{{ route('admin.viewproduct') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.viewproduct') ? 'bg-gray-800' : '' }}">
                        View Product
                    </a>
                    <a href="{{ route('admin.barcode.scanner') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.barcode.scanner') ? 'bg-gray-800' : '' }}">
                        Barcode Scanner
                    </a>
                @endif
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 rounded-md bg-gray-800 hover:bg-gray-700 transition text-sm font-medium">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ml-2 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md hover:bg-gray-800 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-gray-800">
        @if(Auth::check() && Auth::user()->type == 'user')
            <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-900' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('user.barcode.scanner') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('barcode.scanner') ? 'bg-gray-900' : '' }}">
                Barcode Scanner
            </a>
        @elseif(Auth::check() && Auth::user()->type == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-900' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.viewsupplier') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.viewsupplier') ? 'bg-gray-900' : '' }}">
                View Supplier
            </a>
            <a href="{{ route('admin.viewproduct') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.viewproduct') ? 'bg-gray-900' : '' }}">
                View Product
            </a>
            <a href="{{ route('admin.barcode.scanner') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.barcode.scanner') ? 'bg-gray-900' : '' }}">
                Barcode Scanner
            </a>
        @endif

        <!-- Mobile User Dropdown -->
        <div class="border-t border-gray-700">
            <div class="px-4 py-2 text-sm">{{ Auth::user()->name }}</div>
            <div class="px-4 py-2 text-xs text-gray-400">{{ Auth::user()->email }}</div>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-700">
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="block px-4 py-2 text-sm hover:bg-gray-700">
                    Log Out
                </a>
            </form>
        </div>
    </div>
</nav>
