<header class="border-b border-gray-100">
    <div class="max-w-[75rem] mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-4">
            <a href="{{ url('/') }}" class="shrink-0">
                <img src="{{ asset('img/navbar_240x120.png') }}" alt="QRTE" class="h-14 w-auto max-w-[55vw] sm:max-w-none">
            </a>
            <nav class="hidden md:flex items-center gap-10 text-[19px] uppercase text-gray-500">
                <a href="{{ url('/') }}" @class(['hover:text-gray-900 transition', 'text-gray-900' => request()->is('/')])>{{ __('Inicio') }}</a>
                <a href="{{ route('caracteristicas') }}" @class(['hover:text-gray-900 transition', 'text-gray-900' => request()->routeIs('caracteristicas')])>{{ __('Características') }}</a>
                <a href="{{ route('planes') }}" @class(['hover:text-gray-900 transition', 'text-gray-900' => request()->routeIs('planes')])>{{ __('Planes') }}</a>
                <a href="{{ route('ayuda') }}" @class(['hover:text-gray-900 transition', 'text-gray-900' => request()->routeIs('ayuda')])>{{ __('Ayuda') }}</a>
            </nav>
            <div class="flex items-center gap-4 sm:gap-5">
                <x-language-switcher />
                <a href="{{ route('login') }}" class="hidden sm:inline text-lg uppercase text-gray-700 hover:text-gray-900 transition">{{ __('Login') }}</a>
            </div>
        </div>
    </div>
</header>