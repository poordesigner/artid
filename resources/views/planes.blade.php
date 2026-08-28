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
</head>
<body class="font-sans text-gray-900 antialiased bg-white">

    {{-- Navbar --}}
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/navbar_240x120.png') }}" alt="ARTid" class="h-10 w-auto">
                </a>
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">{{ __('Inicio') }}</a>
                    <a href="{{ url('/#caracteristicas') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">{{ __('Características') }}</a>
                    <a href="{{ route('ayuda') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">{{ __('Ayuda') }}</a>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <x-language-switcher />
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 transition">{{ __('Login') }}</a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition">
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

    {{-- Packages --}}
    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($packages->count())
                <div class="grid grid-cols-1 md:grid-cols-{{ min($packages->count(), 3) }} gap-8 max-w-5xl mx-auto">
                    @foreach ($packages as $package)
                        <div class="bg-white rounded-2xl shadow-sm border {{ $loop->first ? 'border-indigo-500 ring-2 ring-indigo-500' : 'border-gray-200' }} flex flex-col overflow-hidden">
                            @if ($loop->first)
                                <div class="px-8 pt-6">
                                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full uppercase tracking-wider">{{ __('Popular') }}</span>
                                </div>
                            @endif

                            <div class="p-8 {{ $loop->first ? 'pt-4' : '' }}">
                                <h3 class="text-xl font-bold text-gray-900">{{ $package->name }}</h3>

                                @if ($package->description)
                                    <p class="mt-2 text-sm text-gray-600">{{ $package->description }}</p>
                                @endif

                                <div class="mt-6">
                                    <div class="flex items-end gap-1">
                                        <span class="text-4xl font-bold text-gray-900">{{ $package->tokens }}</span>
                                        <span class="text-lg text-gray-500 pb-1.5">{{ __('tokens') }}</span>
                                    </div>
                                    <p class="mt-1 text-2xl font-semibold text-gray-700">${{ number_format($package->price_usd, 2) }} USD</p>
                                </div>
                            </div>

                            <div class="px-8 pb-8 flex-1">
                                <ul class="mt-2 space-y-3">
                                    <li class="flex items-start gap-3 text-sm text-gray-700">
                                        <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ __('QR + ficha básica por cada obra') }}
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-gray-700">
                                        <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ __('La ficha pública es permanente') }}
                                    </li>
                                </ul>
                            </div>

                            <div class="px-8 pb-8">
                                <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 {{ $loop->first ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white rounded-lg font-semibold text-sm transition">
                                    {{ __('Empezar') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">{{ __('No hay planes disponibles.') }}</p>
            @endif
        </div>
    </section>

    {{-- Usos de tokens --}}
    <section class="pb-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('¿Cómo se usan los tokens?') }}</h2>
                <p class="mt-2 text-gray-600">{{ __('Cada función de la plataforma consume la cantidad de tokens indicada.') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Función') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tokens') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($tokenFunctions as $tf)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $tf->name }}</p>
                                    @if ($tf->description)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $tf->description }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">{{ $tf->tokens }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500 text-center" colspan="2">{{ __('No hay funciones definidas.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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