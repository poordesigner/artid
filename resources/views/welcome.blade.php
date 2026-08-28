<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon_192x192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon_192x192.png') }}">
    <title>{{ __('Identidad Digital para Obras de Arte') }} — ARTid</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Muli:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Muli', ui-sans-serif, system-ui, sans-serif; }
        .tracking-gallery { letter-spacing: 0.25em; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- Navbar --}}
    <header class="border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                <a href="{{ url('/') }}" class="shrink-0">
                    <img src="{{ asset('img/navbar_240x120.png') }}" alt="ARTid" class="h-11 w-auto max-w-[50vw] sm:max-w-none">
                </a>
                <nav class="hidden md:flex items-center gap-10 text-base text-gray-500">
                    <a href="{{ route('planes') }}" class="hover:text-gray-900 transition">{{ __('Planes') }}</a>
                    <a href="#caracteristicas" class="hover:text-gray-900 transition">{{ __('Características') }}</a>
                    <a href="{{ route('ayuda') }}" class="hover:text-gray-900 transition">{{ __('Ayuda') }}</a>
                </nav>
                <div class="flex items-center gap-4 sm:gap-5">
                    <x-language-switcher />
                    <a href="{{ route('login') }}" class="hidden sm:inline text-base text-gray-700 hover:text-gray-900 transition">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="text-base px-5 py-2.5 border border-gray-900 hover:bg-gray-900 hover:text-white transition">
                        {{ __('Empezar') }}
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="font-medium text-5xl sm:text-6xl lg:text-7xl leading-[1.1] tracking-tight text-gray-900">
                {{ __('Identidad digital para tus obras de arte.') }}
            </h1>
            <div class="mx-auto mt-10 h-px w-24 bg-gray-300"></div>
            <p class="mt-10 text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                {{ __('Genera una ficha técnica permanente para cada obra. Código QR único, metadata verificada y control de propiedad cifrado.') }}
            </p>
            <div class="mt-12 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3 border border-gray-900 bg-gray-900 text-white text-sm tracking-gallery uppercase hover:bg-white hover:text-gray-900 transition">
                    {{ __('Empezar ahora') }}
                </a>
                <a href="{{ route('planes') }}" class="px-8 py-3 border border-gray-900 text-gray-900 text-sm tracking-gallery uppercase hover:bg-gray-900 hover:text-white transition">
                    {{ __('Ver Planes') }}
                </a>
                <a href="{{ route('ayuda') }}" class="px-8 py-3 text-gray-500 text-sm tracking-gallery uppercase hover:text-gray-900 transition">
                    {{ __('Ver guía') }}
                </a>
            </div>
            <p class="mt-10 text-xs text-gray-400 max-w-md mx-auto">
                {{ __('QR permanente y firmado criptográficamente. Solo la obra auténtica accede a su ficha pública.') }}
            </p>
        </div>
    </section>

    {{-- Ficha simulada --}}
    <section id="galeria" class="pb-24">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="border border-gray-200 p-10 sm:p-14">
                <div class="flex items-start justify-between pb-6 border-b border-gray-200">
                    <span class="text-sm font-semibold uppercase tracking-gallery text-gray-900">{{ __('Obra autenticada') }}</span>
                    <span class="px-2.5 py-1 text-xs uppercase tracking-wider text-gray-500 border border-gray-200">✓ {{ __('Verificado') }}</span>
                </div>
                <div class="mt-8 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">{{ __('Serie') }}</span>
                        <span class="text-sm font-medium text-gray-900">Paisajes Urbanos</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">{{ __('Editorial') }}</span>
                        <span class="text-sm font-medium text-gray-900">2 / 5</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">{{ __('Técnica') }}</span>
                        <span class="text-sm font-medium text-gray-900">Óleo sobre lienzo</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('Dimensiones') }}</span>
                        <span class="text-sm font-medium text-gray-900">50 x 70 cm</span>
                    </div>
                </div>
                <div class="mt-10 flex items-center justify-center gap-6 p-6 bg-gray-50">
                    <div class="w-16 h-16 bg-white flex items-center justify-center border border-gray-200">
                        <div class="grid grid-cols-5 gap-0.5">
                            @for ($i = 0; $i < 25; $i++)
                                <div class="{{ in_array($i % 7, [0, 1, 3, 5]) ? 'bg-gray-900' : 'bg-white' }} w-1.5 h-1.5"></div>
                            @endfor
                        </div>
                    </div>
                    <p class="text-xs uppercase tracking-gallery text-gray-500">{{ __('Ficha pública verificada') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Características --}}
    <section id="caracteristicas" class="pb-24">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="text-center">
                <p class="text-xs font-semibold uppercase tracking-gallery text-gray-500">{{ __('Cómo funciona') }}</p>
                <h2 class="mt-4 font-medium text-3xl sm:text-4xl text-gray-900">{{ __('Todo lo que necesitas para autenticar tus obras.') }}</h2>
            </div>
            <hr class="mt-12 border-gray-200">
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-12">
                <div>
                    <p class="text-5xl font-light text-gray-200">01</p>
                    <h3 class="mt-4 text-lg font-semibold tracking-wide text-gray-900">{{ __('Ficha de Datos') }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ __('Crea una ficha técnica completa: título, año, edición, serie, técnicas, dimensiones, descripción e imagen.') }}</p>
                </div>
                <div>
                    <p class="text-5xl font-light text-gray-200">02</p>
                    <h3 class="mt-4 text-lg font-semibold tracking-wide text-gray-900">{{ __('QR Permanente') }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ __('Cada obra tiene un código QR único que nunca cambia. Se imprime sobre la obra y dirige a la ficha pública.') }}</p>
                </div>
                <div>
                    <p class="text-5xl font-light text-gray-200">03</p>
                    <h3 class="mt-4 text-lg font-semibold tracking-wide text-gray-900">{{ __('Llaves Cifradas') }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ __('La relación entre ficha y QR está firmada criptográficamente. Solo la obra auténtica accede a su ficha.') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="border-t border-gray-200 py-24">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="font-medium text-4xl sm:text-5xl text-gray-900">{{ __('Empieza a proteger tus obras hoy.') }}</h2>
            <p class="mt-6 text-lg text-gray-600">{{ __('Crea tu cuenta y genera la identidad digital de tu primera obra en minutos.') }}</p>
            <a href="{{ route('register') }}" class="mt-10 inline-block px-10 py-3.5 border border-gray-900 bg-gray-900 text-white text-sm tracking-gallery uppercase hover:bg-white hover:text-gray-900 transition">
                {{ __('Crear mi cuenta') }}
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 py-12">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/navbar_240x120.png') }}" alt="ARTid" class="h-8 w-auto">
                    <span class="text-sm text-gray-400">by <a href="https://poordesigner.com" class="text-gray-500 hover:text-gray-900 transition" target="_blank" rel="noopener">POORdesigner.com</a></span>
                </div>
                <div class="flex items-center gap-8 text-sm text-gray-500">
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