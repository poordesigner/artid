<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon_192x192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon_192x192.png') }}">
    <title>{{ __('Identidad Digital para Obras de Arte') }} — QRTE</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Space+Grotesk:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif; }
        .tracking-gallery { letter-spacing: 0.25em; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    @include('partials.public-navbar')

    {{-- Hero --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-[75rem] mx-auto px-6 lg:px-8 text-center">
            <h1 class="font-medium text-4xl sm:text-5xl md:text-[5rem] leading-[1.1] sm:leading-[1.05] tracking-tight text-gray-900 max-w-6xl mx-auto">
                {{ __('Identidad Digital para tus obras de arte.') }}
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
                    <p class="text-xs uppercase tracking-gallery text-gray-400">{{ __('REGALO DE BIENVENIDA') }}</p>
                    <h2 class="mt-4 font-medium text-2xl sm:text-3xl">
                        {{ __('Recibe :count tokens gratis al registrarte', ['count' => config('QRTE.welcome_tokens', 0)]) }}
                    </h2>
                    <p class="mt-3 text-gray-300">{{ __('Crea la Identidad Digital de tus primeras obras') }}</p>
                    <p class="mt-1 text-gray-300">{{ __('Sin Suscripción - Sin Tarjeta') }}</p>
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

    @include('partials.public-footer')

    <x-chatwoot-widget />
</body>
</html>