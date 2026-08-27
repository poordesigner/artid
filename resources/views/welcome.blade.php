<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon_192x192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon_192x192.png') }}">
    <title>{{ __('Identidad Digital para Obras de Arte') }} — ARTid</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
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
                    <a href="{{ route('planes') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">{{ __('Planes') }}</a>
                    <a href="#caracteristicas" class="text-sm text-gray-500 hover:text-gray-700 transition">{{ __('Características') }}</a>
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
    <section class="py-20 sm:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Izquierda: imagen --}}
                <div>
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-3xl blur-2xl opacity-60"></div>
                        <img src="{{ asset('img/img_home_1.png') }}" alt="ARTid" class="relative rounded-2xl shadow-xl border border-gray-100 w-full h-auto">
                    </div>
                </div>

                {{-- Derecha: texto arriba + ficha abajo --}}
                <div class="space-y-10">
                    {{-- Texto --}}
                    <div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-[1.1] tracking-tight">
                            {{ __('Identidad digital para tus obras de arte.') }}
                        </h1>
                        <p class="mt-6 text-lg text-gray-600 leading-relaxed">
                            {{ __('Genera una ficha técnica permanente para cada obra. Código QR único, metadata verificada y control de propiedad cifrado.') }}
                        </p>
                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition">
                                {{ __('Empezar ahora') }}
                                <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <a href="{{ route('planes') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                                {{ __('Ver Planes') }}
                            </a>
                            <a href="{{ route('ayuda') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition">
                                {{ __('Ver guía') }}
                            </a>
                        </div>
                        <p class="mt-6 text-xs text-gray-400 max-w-md">
                            {{ __('QR permanente y firmado criptográficamente. Solo la obra auténtica accede a su ficha pública.') }}
                        </p>
                    </div>

                    {{-- Ficha simulada --}}
                    <div class="relative">
                        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-900">{{ __('Obra autenticada') }}</span>
                                <span class="px-2 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-full">✓ {{ __('Verificado') }}</span>
                            </div>
                            <div class="mt-6 space-y-5">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ __('Serie') }}</span>
                                    <span class="text-sm font-medium text-gray-900">Paisajes Urbanos</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ __('Editorial') }}</span>
                                    <span class="text-sm font-medium text-gray-900">2 / 5</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ __('Técnica') }}</span>
                                    <span class="text-sm font-medium text-gray-900">Óleo sobre lienzo</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ __('Dimensiones') }}</span>
                                    <span class="text-sm font-medium text-gray-900">50 x 70 cm</span>
                                </div>
                            </div>
                            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                                <div class="w-16 h-16 bg-white mx-auto flex items-center justify-center border border-gray-200 rounded-lg">
                                    <div class="grid grid-cols-5 gap-0.5">
                                        @for ($i = 0; $i < 25; $i++)
                                            <div class="{{ in_array($i % 7, [0, 1, 3, 5]) ? 'bg-gray-900' : 'bg-white' }} w-1.5 h-1.5"></div>
                                        @endfor
                                    </div>
                                </div>
                                <p class="mt-3 text-center text-xs text-gray-500">{{ __('Ficha pública verificada') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="caracteristicas" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider">{{ __('Cómo funciona') }}</p>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">{{ __('Todo lo que necesitas para autenticar tus obras.') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">{{ __('Ficha de Datos') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('Crea una ficha técnica completa: título, año, edición, serie, técnicas, dimensiones, descripción e imagen.') }}</p>
                </div>
                <div class="p-8 bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">{{ __('QR Permanente') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('Cada obra tiene un código QR único que nunca cambia. Se imprime sobre la obra y dirige a la ficha pública.') }}</p>
                </div>
                <div class="p-8 bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">{{ __('Llaves Cifradas') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('La relación entre ficha y QR está firmada criptográficamente. Solo la obra auténtica accede a su ficha.') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Footer --}}
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white">{{ __('Empieza a proteger tus obras hoy.') }}</h2>
            <p class="mt-4 text-lg text-gray-300 max-w-xl mx-auto">{{ __('Crea tu cuenta y genera la identidad digital de tu primera obra en minutos.') }}</p>
            <div class="mt-8">
                <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3 bg-white hover:bg-gray-100 text-gray-900 text-sm font-semibold rounded-lg transition">
                    {{ __('Crear mi cuenta') }}
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
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
                    <a href="{{ route('planes') }}" class="hover:text-white transition">{{ __('Planes') }}</a>
                    <a href="#caracteristicas" class="hover:text-white transition">{{ __('Características') }}</a>
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
