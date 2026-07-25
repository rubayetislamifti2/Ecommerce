<nav class="bg-white border-b border-gray-100 shadow-sm" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Logo & Left Links -->
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center font-bold text-xl text-indigo-600">
                    <svg class="w-8 h-8 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    MyBrand
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden sm:flex sm:space-x-8 sm:ml-10">
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-gray-900 font-semibold text-sm">
                        Home
                    </a>
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        About
                    </a>
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Services
                    </a>
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Contact
                    </a>
                </div>
            </div>

            <!-- Right Side Auth / Buttons -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                @auth

                    <a href="{{ route('order.index') }}" class="relative p-2 text-gray-600 hover:text-indigo-600 transition" title="View Cart / Orders">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>

                        @php
                            $cartCount = auth()->user()->orders()->where('status', 'pending')->count();
                        @endphp

                        @if($cartCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-indigo-600 rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Dropdown Menu -->
                    <div class="relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
                            <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': userMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Panel -->
                        <div x-show="userMenuOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                             style="display: none;">

                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                My Orders
                            </a>

                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Purchased Items
                            </a>

                            <a href="{{route('payment.method')}}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H5a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Add Payment Method
                            </a>

                            <div class="border-t border-gray-100 my-2"></div>

                            <form method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition text-left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <a href="{{ route('login-page') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Log in</a>
                    <a href="{{ route('register-page') }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                        Register
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-b border-gray-200 bg-gray-50">
        <div class="pt-2 pb-3 space-y-1">
            <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-indigo-500 text-indigo-700 bg-indigo-50 font-semibold text-base">Home</a>
            <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:bg-gray-100">About</a>
            <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:bg-gray-100">Services</a>
            <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:bg-gray-100">Contact</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200 px-4 space-y-2">
            @auth
                <!-- Username display -->
                <div class="flex items-center gap-2 px-1 py-2">
                    <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </span>
                    <span class="font-semibold text-gray-800">{{ auth()->user()->name }}</span>
                </div>

                <!-- Mobile View Cart Link -->
                <a href="{{ route('order.index') }}" class="flex items-center justify-between text-base font-medium text-gray-600 hover:text-indigo-600">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        My Orders
                    </span>
                    <span class="bg-indigo-600 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ auth()->user()->orders()->where('status', 'pending')->count() }}
                    </span>
                </a>

                <a href="#" class="block text-base font-medium text-gray-600">Purchased Items</a>
                <a href="{{route('payment.method')}}" class="block text-base font-medium text-gray-600">Add Payment Method</a>

                <form method="POST" action="{{ route('auth.logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full text-left text-base font-medium text-red-600">Logout</button>
                </form>
            @else
                <a href="{{ route('login-page') }}" class="block text-base font-medium text-gray-600">Log in</a>
                <a href="{{ route('register-page') }}" class="block w-full text-center py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg">Register</a>
            @endauth
        </div>
    </div>
</nav>
