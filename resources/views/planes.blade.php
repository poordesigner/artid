<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon_192x192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon_192x192.png') }}">
    <title>{{ __('Planes') }} — ARTid</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Space+Grotesk:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif; }
        .tracking-gallery { letter-spacing: 0.25em; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- Navbar --}}
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="shrink-0">
                    <img src="{{ asset('img/navbar_240x120.png') }}" alt="ARTid" class="h-14 w-auto max-w-[45vw] sm:max-w-none">
                </a>
                <nav class="hidden md:flex items-center gap-8 text-[19px] uppercase">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-900 transition">{{ __('Inicio') }}</a>
                    <a href="{{ url('/#caracteristicas') }}" class="text-gray-500 hover:text-gray-900 transition">{{ __('Características') }}</a>
                    <a href="{{ route('ayuda') }}" class="text-gray-500 hover:text-gray-900 transition">{{ __('Ayuda') }}</a>
                </nav>
            </div>
            <div class="flex items-center gap-3 sm:gap-4">
                <x-language-switcher />
                <a href="{{ route('login') }}" class="text-lg font-medium uppercase text-gray-700 hover:text-gray-900 transition">{{ __('Login') }}</a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-lg font-semibold uppercase rounded-lg transition">
                    {{ __('Empezar') }}
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900">{{ __('Paquetes de tokens') }}</h1>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">{{ __('Elige el plan que mejor se adapte a tus necesidades.') }}</p>
        </div>
    </section>

    {{-- Bienvenida free --}}
    <section class="-mt-6 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-white p-8 sm:p-10 flex flex-col sm:flex-row items-center justify-between gap-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">{{ __('Regalo de bienvenida') }}</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl font-bold">
                        {{ __(':count tokens gratis al registrarte', ['count' => config('artid.welcome_tokens', 0)]) }}
                    </h2>
                    <p class="mt-2 text-gray-300 max-w-xl">{{ __('Con ellos creas tu primera obra con QR y ficha básica. Sin suscripción, sin tarjeta.') }}</p>
                </div>
                <a href="{{ route('register') }}" class="shrink-0 px-8 py-3 bg-white text-gray-900 font-semibold text-sm uppercase tracking-wider hover:bg-gray-200 transition">
                    {{ __('Crear cuenta gratis') }}
                </a>
            </div>
        </div>
    </section>

    {{-- Paquetes + usos --}}
    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($packages->count())
                <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr_320px] gap-10"
                     x-data="packageSelector(@js($packages->map(fn ($p) => [
                         'id' => $p->id,
                         'name' => $p->name,
                         'tokens' => $p->tokens,
                         'price' => (float) $p->price_usd,
                         'perToken' => round((float) $p->price_usd / $p->tokens, 2),
                     ])->all()))">

                    {{-- Columna 1: lista de paquetes --}}
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-gallery text-gray-500">{{ __('Paquetes') }}</p>
                        @foreach ($packages as $package)
                            <button type="button" @mouseenter="selected = {{ $package->id }}"
                                @focus="selected = {{ $package->id }}"
                                :class="selected === {{ $package->id }} ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-200 bg-white text-gray-900 hover:border-gray-400'"
                                class="w-full flex items-center justify-between px-5 py-4 border rounded-lg text-left transition">
                                <span class="font-semibold">{{ $package->name }}</span>
                                <span class="text-sm">
                                    ${{ number_format($package->price_usd, 0) }} USD
                                </span>
                                @if ($loop->first)
                                    <span class="ml-2 shrink-0 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider rounded-full {{ $loop->first ? 'bg-indigo-100 text-indigo-700' : '' }}">
                                        {{ __('Popular') }}
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    {{-- Columna 2: detalle del paquete (se actualiza con hover) --}}
                    <div class="flex flex-col justify-center">
                        <div class="border border-gray-200 p-8 sm:p-10" x-cloak>
                            <template x-for="p in packages" :key="p.id">
                                <div x-show="selected === p.id">
                                    <p class="text-xs font-semibold uppercase tracking-gallery text-gray-500" x-text="p.name"></p>
                                    <p class="mt-4 text-6xl font-bold text-gray-900 leading-none">
                                        <span x-text="p.tokens"></span>
                                        <span class="text-2xl font-medium text-gray-500">{{ __('tokens') }}</span>
                                    </p>
                                    <p class="mt-4 text-xl text-gray-600">
                                        <span x-text="'$' + formatUsd(p.perToken)"></span>
                                        <span class="text-sm">{{ __('USD/token') }}</span>
                                    </p>
                                    <p class="mt-2 text-3xl font-semibold text-gray-900">
                                        <span x-text="'$' + formatUsd(p.price)"></span>
                                        <span class="text-base text-gray-500">USD</span>
                                    </p>
                                    <a :href="'{{ route('register') }}'"
                                        class="mt-8 inline-flex items-center justify-center w-full px-6 py-4 bg-gray-900 hover:bg-gray-700 text-white text-sm font-semibold uppercase tracking-gallery transition">
                                        {{ __('Seleccionar') }}
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Columna 3: usos de tokens --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-gallery text-gray-500">{{ __('Usos de tokens') }}</p>
                        <p class="mt-2 text-sm text-gray-600">{{ __('Cada función consume la cantidad de tokens indicada.') }}</p>
                        <div class="mt-4 space-y-3">
                            @forelse ($tokenFunctions as $tf)
                                <div class="border border-gray-200 rounded-lg px-5 py-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-sm font-medium text-gray-900">{{ $tf->name }}</span>
                                        <span class="text-sm font-semibold text-gray-700">
                                            <span class="text-lg font-bold">{{ $tf->tokens }}</span>
                                            {{ $tf->tokens === 1 ? __('token') : __('tokens') }}
                                        </span>
                                    </div>
                                    @if ($tf->actions->isNotEmpty())
                                        <div class="mt-2 flex flex-wrap gap-1.5">
                                            @foreach ($tf->actions as $action)
                                                <span class="px-2 py-0.5 text-[10px] bg-gray-100 text-gray-600 rounded-full">{{ $action->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">{{ __('No hay funciones definidas.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">{{ __('No hay planes disponibles.') }}</p>
            @endif
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-12 bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/navbar_240x120.png') }}" alt="ARTid" class="h-9 w-auto">
                    <span class="text-sm text-gray-400">by <a href="https://poordesigner.com" class="text-gray-300 hover:text-white" target="_blank" rel="noopener">POORdesigner.com</a></span>
                </div>
                <div class="flex items-center gap-8 text-sm text-gray-400">
                    <a href="{{ route('ayuda') }}" class="hover:text-white transition">{{ __('Ayuda') }}</a>
                    <a href="{{ url('/') }}" class="hover:text-white transition">{{ __('Inicio') }}</a>
                    <a href="{{ url('/#caracteristicas') }}" class="hover:text-white transition">{{ __('Características') }}</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} POORdesigner.com. {{ __('Todos los derechos reservados.') }}
                </p>
            </div>
        </div>
    </footer>
</body>
</html>