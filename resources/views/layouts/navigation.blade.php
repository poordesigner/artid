<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()?->isAdmin() ? route('admin.dashboard') : route('dashboard.artist') }}">
                        <img src="{{ asset('img/navbar_240x120.png') }}" alt="QRTE" class="block h-10 w-auto">
                    </a>
                </div>
            </div>

            <!-- Right side: menu + settings dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-8">
                <!-- Navigation Links -->
                @if (Auth::user()?->isAdmin())
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        {{ __('Panel') }}
                    </x-nav-link>
                @else
                    <x-nav-link :href="route('dashboard.artist')" :active="request()->routeIs('dashboard.*')">
                        {{ __('Panel') }}
                    </x-nav-link>
                    <x-nav-link :href="route('artworks.index')" :active="request()->routeIs('artworks.*')">
                        {{ __('Obras') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tokens.index')" :active="request()->routeIs('tokens.*')">
                        {{ __('Mis tokens') }}
                    </x-nav-link>
                @endif
                <x-nav-link :href="route('configuracion')" :active="request()->routeIs('configuracion')">
                    {{ __('Configuración') }}
                </x-nav-link>
                <x-nav-link :href="route('ayuda')" :active="request()->routeIs('ayuda')">
                    {{ __('Ayuda') }}
                </x-nav-link>

                @if (! Auth::user()?->isAdmin())
                    @php($unread = Auth::user()?->notifications()->unread()->count() ?? 0)
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150" title="{{ __('Notificaciones') }}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if ($unread > 0)
                                    <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full bg-red-500 text-white text-[10px] font-bold">{{ $unread > 99 ? '99+' : $unread }}</span>
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 px-3 text-xs uppercase tracking-wider text-gray-400">{{ __('Notificaciones') }}</div>
                            @php($recent = Auth::user()?->notifications()->take(5)->get() ?? collect())
                            @if ($recent->isEmpty())
                                <p class="px-3 py-2 text-sm text-gray-500">{{ __('No tienes notificaciones.') }}</p>
                            @else
                                @foreach ($recent as $notification)
                                    <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                        @csrf
                                        <x-dropdown-link :href="route('notifications.read', $notification)"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                            <span class="flex items-start gap-2 min-w-0">
                                                <span class="shrink-0 mt-1.5 w-2 h-2 rounded-full {{ $notification->isRead() ? 'bg-gray-200' : 'bg-indigo-500' }}"></span>
                                                <span class="min-w-0">
                                                    <span class="block text-sm font-medium text-gray-900 truncate">{{ $notification->title }}</span>
                                                    @if ($notification->body)
                                                        <span class="block text-xs text-gray-500 line-clamp-1">{{ $notification->body }}</span>
                                                    @endif
                                                </span>
                                            </span>
                                        </x-dropdown-link>
                                    </form>
                                @endforeach
                                <div class="border-t border-gray-100">
                                    <x-dropdown-link :href="route('notifications.index')">
                                        {{ __('Ver todas') }}
                                    </x-dropdown-link>
                                </div>
                            @endif
                        </x-slot>
                    </x-dropdown>
                @endif

                <x-language-switcher />

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('configuracion')">
                            {{ __('Configuración') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
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
            @if (Auth::user()?->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                    {{ __('Panel') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard.artist')" :active="request()->routeIs('dashboard.*')">
                    {{ __('Panel') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('artworks.index')" :active="request()->routeIs('artworks.*')">
                    {{ __('Obras') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('tokens.index')" :active="request()->routeIs('tokens.*')">
                    {{ __('Mis tokens') }}
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('configuracion')" :active="request()->routeIs('configuracion')">
                {{ __('Configuración') }}
            </x-responsive-nav-link>
<x-responsive-nav-link :href="route('ayuda')" :active="request()->routeIs('ayuda')">
                    {{ __('Ayuda') }}
                </x-responsive-nav-link>
                @if (! Auth::user()?->isAdmin())
                    <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                        {{ __('Notificaciones') }}
                    </x-responsive-nav-link>
                @endif
            </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('configuracion')">
                    {{ __('Configuración') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
