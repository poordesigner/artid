<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon_192x192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon_192x192.png') }}">
    <title>{{ __('Identidad Digital para Obras de Arte') }} — ARTid</title>
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
    <header class="border-b border-gray-100">
        <div class="max-w-[75rem] mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                <a href="{{ url('/') }}" class="shrink-0">
                    <img src="{{ asset('img/navbar_240x120.png') }}" alt="ARTid" class="h-14 w-auto max-w-[55vw] sm:max-w-none">
                </a>
                <nav class="hidden md:flex items-center gap-10 text-[19px] uppercase text-gray-500">
                    <a href="{{ route('planes') }}" class="hover:text-gray-900 transition">{{ __('Planes') }}</a>
                    <a href="#caracteristicas" class="hover:text-gray-900 transition">{{ __('Características') }}</a>
                    <a href="{{ route('ayuda') }}" class="hover:text-gray-900 transition">{{ __('Ayuda') }}</a>
                </nav>
                <div class="flex items-center gap-4 sm:gap-5">
                    <x-language-switcher />
                    <a href="{{ route('login') }}" class="hidden sm:inline text-lg uppercase text-gray-700 hover:text-gray-900 transition">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="text-lg uppercase px-5 py-2.5 border border-brand hover:bg-brand hover:text-white transition">
                        {{ __('Empezar') }}
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-[75rem] mx-auto px-6 lg:px-8 text-center">
            <h1 class="font-medium text-[5rem] leading-[1.05] tracking-tight text-gray-900 max-w-6xl mx-auto">
                {{ __('Identidad digital para tus obras de arte.') }}
            </h1>
            <div class="mx-auto mt-10 h-px w-32 bg-gray-300"></div>
            <p class="mt-10 text-xl sm:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed font-light">
                {{ __('Genera una ficha técnica permanente para cada obra. Código QR único, metadata verificada y control de propiedad cifrado.') }}
            </p>

            {{-- Bienvenida + acciones (sección dividida) --}}
            <div class="mt-14 grid grid-cols-1 lg:grid-cols-3 gap-10 items-stretch">
                {{-- Izquierda: regalo de bienvenida (66%) --}}
                <div class="flex lg:col-span-2 border border-brand text-white p-8 sm:p-10 flex-col justify-center"
                     style="background: #550044;">
                    <p class="text-xs uppercase tracking-gallery text-gray-400">{{ __('Regalo de bienvenida') }}</p>
                    <h2 class="mt-4 font-medium text-2xl sm:text-3xl">
                        {{ __('Recibe :count tokens gratis al registrarte.', ['count' => config('artid.welcome_tokens', 0)]) }}
                    </h2>
                    <p class="mt-3 text-gray-300">
                        {{ __('Con ellos creas tu primera obra con QR y ficha básica. Sin suscripción, sin tarjeta.') }}
                    </p>
                </div>

                {{-- Derecha: botones de acción --}}
                <div class="flex flex-col justify-center gap-5">
                    <a href="{{ route('register') }}" class="px-10 py-4 border border-brand bg-white text-gray-900 text-base tracking-gallery uppercase text-center hover:bg-brand hover:text-white transition">
                        {{ __('Crear cuenta gratis') }}
                    </a>
                    <a href="{{ route('planes') }}" class="px-10 py-4 border border-brand bg-white text-gray-900 text-base tracking-gallery uppercase text-center hover:bg-brand hover:text-white transition">
                        {{ __('Ver Planes') }}
                    </a>
                    <a href="{{ route('ayuda') }}" class="px-10 py-4 border border-brand bg-white text-gray-900 text-base tracking-gallery uppercase text-center hover:bg-brand hover:text-white transition">
                        {{ __('Ver guía') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Ficha + Cómo funciona (sección dividida) --}}
    <section id="caracteristicas" class="pb-28">
        <div class="max-w-[75rem] mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-stretch">

                {{-- Izquierda: ficha simulada --}}
                <div class="border border-gray-200 p-8 sm:p-10">
                    <div class="flex items-start justify-between pb-8 border-b border-gray-200">
                        <span class="text-base font-semibold uppercase tracking-gallery text-gray-900">{{ __('Obra autenticada') }}</span>
                        <span class="px-3 py-1.5 text-sm uppercase tracking-wider text-gray-500 border border-gray-200">✓ {{ __('Verificado') }}</span>
                    </div>
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-8">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Serie') }}</p>
                            <p class="mt-2 text-lg font-medium text-gray-900">Paisajes Urbanos</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Editorial') }}</p>
                            <p class="mt-2 text-lg font-medium text-gray-900">2 / 5</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Técnica') }}</p>
                            <p class="mt-2 text-lg font-medium text-gray-900">Óleo sobre lienzo</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Dimensiones') }}</p>
                            <p class="mt-2 text-lg font-medium text-gray-900">50 x 70 cm</p>
                        </div>
                    </div>
                    <div class="mt-8 flex items-center justify-center gap-6 p-6 bg-gray-50">
                        <div class="w-20 h-20 bg-white flex items-center justify-center border border-gray-200">
                            <div class="grid grid-cols-5 gap-0.5">
                                @for ($i = 0; $i < 25; $i++)
                                    <div class="{{ in_array($i % 7, [0, 1, 3, 5]) ? 'bg-brand' : 'bg-white' }} w-1.5 h-1.5"></div>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm uppercase tracking-gallery text-gray-500">{{ __('Ficha pública verificada') }}</p>
                    </div>
                </div>

                {{-- Derecha: Cómo funciona --}}
                <div class="p-8 sm:p-10 flex flex-col justify-center">
                    <p class="text-3xl sm:text-4xl font-medium tracking-gallery uppercase text-gray-900">{{ __('Cómo funciona') }}</p>
                    <hr class="mt-8 border-gray-200">
                    <div class="mt-10 space-y-10">
                        <div>
                            <p class="text-4xl font-light text-gray-200">01</p>
                            <h3 class="mt-3 text-lg font-semibold tracking-wide text-gray-900">{{ __('Ficha de Datos') }}</h3>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('Crea una ficha técnica completa: título, año, edición, serie, técnicas, dimensiones, descripción e imagen.') }}</p>
                        </div>
                        <div>
                            <p class="text-4xl font-light text-gray-200">02</p>
                            <h3 class="mt-3 text-lg font-semibold tracking-wide text-gray-900">{{ __('QR Permanente') }}</h3>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('Cada obra tiene un código QR único que nunca cambia. Se imprime sobre la obra y dirige a la ficha pública.') }}</p>
                        </div>
                        <div>
                            <p class="text-4xl font-light text-gray-200">03</p>
                            <h3 class="mt-3 text-lg font-semibold tracking-wide text-gray-900">{{ __('Llaves Cifradas') }}</h3>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('La relación entre ficha y QR está firmada criptográficamente. Solo la obra auténtica accede a su ficha.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 py-12">
        <div class="max-w-[75rem] mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/navbar_240x120.png') }}" alt="ARTid" class="h-8 w-auto">
                    <span class="text-sm text-gray-400">by <a href="https://poordesigner.com" class="text-gray-500 hover:text-gray-900 transition" target="_blank" rel="noopener">POORdesigner.com</a></span>
                </div>
                <div class="flex items-center gap-8 text-sm text-gray-500">
                    <a href="{{ route('login') }}" class="hover:text-gray-900 transition">{{ __('Login') }}</a>
                    <a href="{{ route('ayuda') }}" class="hover:text-gray-900 transition">{{ __('Ayuda') }}</a>
                    <a href="{{ route('planes') }}" class="hover:text-gray-900 transition">{{ __('Planes') }}</a>
                    <a href="#caracteristicas" class="hover:text-gray-900 transition">{{ __('Características') }}</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} POORdesigner.com. {{ __('Todos los derechos reservados.') }}
                </p>
            </div>
        </div>
    </footer>
</body>
</html>