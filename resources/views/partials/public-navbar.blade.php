<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('img/navbar_240x120.png') }}" alt="QRTE" class="block h-10 w-auto">
                    </a>
                </div>
            </div>

            <!-- Right side: menu -->
            <div class="hidden sm:flex sm:items-center sm:gap-8">
                <!-- Navigation Links -->
                <x-nav-link :href="url('/')" :active="request()->is('/')">
                    {{ __('Inicio') }}
                </x-nav-link>
                <x-nav-link :href="route('caracteristicas')" :active="request()->routeIs('caracteristicas')">
                    {{ __('Características') }}
                </x-nav-link>
                <x-nav-link :href="route('planes')" :active="request()->routeIs('planes')">
                    {{ __('Planes') }}
                </x-nav-link>
                <x-nav-link :href="route('ayuda')" :active="request()->routeIs('ayuda')">
                    {{ __('Ayuda') }}
                </x-nav-link>

                <x-language-switcher />

                <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                    {{ __('Login') }}
                </a>
            </div>

            <!-- Hamburger (móvil) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
                {{ __('Inicio') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('caracteristicas')" :active="request()->routeIs('caracteristicas')">
                {{ __('Características') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('planes')" :active="request()->routeIs('planes')">
                {{ __('Planes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('ayuda')" :active="request()->routeIs('ayuda')">
                {{ __('Ayuda') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('login')">
                {{ __('Login') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>