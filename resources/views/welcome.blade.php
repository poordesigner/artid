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
                </div>
            </div>
        </div>
    </header>

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
                        {{ __('Recibe :count tokens gratis al registrarte', ['count' => config('artid.welcome_tokens', 0)]) }}
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

    {{-- Ficha + Cómo funciona (sección dividida) --}}
    <section id="caracteristicas" class="pb-28">
        <div class="max-w-[75rem] mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-stretch">

                {{-- Izquierda: Cómo funciona --}}
                <div class="p-8 sm:p-10 flex flex-col justify-center">
                    <p class="text-3xl sm:text-4xl font-medium tracking-gallery uppercase text-gray-900">{{ __('Cómo funciona') }}</p>
                    <hr class="mt-8 border-gray-200">
                    <div class="mt-10 space-y-6">
                        <div>
                            <h3 class="mt-2 text-base font-semibold tracking-wide text-gray-900">{{ __('Ficha de Datos Digital') }}</h3>
                            <p class="mt-1 text-xs text-gray-600 leading-relaxed">{{ __('Crea una ficha técnica básica de tu obra: título, año, edición, serie, técnicas, dimensiones, descripción, imagen y links externos.') }}</p>
                        </div>
                        <div>
                            <h3 class="mt-2 text-base font-semibold tracking-wide text-gray-900">{{ __('QR Permanente') }}</h3>
                            <p class="mt-1 text-xs text-gray-600 leading-relaxed">{{ __('Cada obra se asocia con un QR único que nunca cambia y es el medio de acceso fácil a la ficha técnica de la misma. Lo puedes usar como quieras: imprimir, pegar a tu obra, incluir en páginas web o donde lo consideres conveniente.') }}</p>
                        </div>
                        <div>
                            <h3 class="mt-2 text-base font-semibold tracking-wide text-gray-900">{{ __('Llaves Cifradas') }}</h3>
                            <p class="mt-1 text-xs text-gray-600 leading-relaxed">{{ __('La relación entre la ficha técnica y la ruta de acceso del código QR está firmada criptográficamente. Solo la obra auténtica accede a su ficha.') }}</p>
                        </div>
                        <div>
                            <h3 class="mt-2 text-base font-semibold tracking-wide text-gray-900">{{ __('Historial de Exposiciones') }}</h3>
                            <p class="mt-1 text-xs text-gray-600 leading-relaxed">{{ __('Documenta el historial de exposiciones asociadas a tu obra. Registrando el lugar, las fechas y links externos de cada exposición.') }}</p>
                        </div>
                        <div>
                            <h3 class="mt-2 text-base font-semibold tracking-wide text-gray-900">{{ __('Procedencia (Historial de Propiedad) y Certificado de Autenticidad (COA)') }}</h3>
                            <p class="mt-1 text-xs text-gray-600 leading-relaxed">{{ __('Si la obra deja de ser de la tenencia del artista y pasa a un nuevo propietario, se puede crear un nuevo registro de procedencia (con los datos básicos o anónimos del nuevo propietario) y generar un Certificado de Autenticidad (COA) con un ID criptográfico especial. El nuevo propietario, al acceder a la ficha técnica de la obra con el código QR, podrá validar la legalidad de la misma usando el ID criptográfico asociado a su COA y tener una herramienta adicional para demostrar su derecho de tenencia de la obra.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Derecha: ficha simulada --}}
                <div class="bg-brand text-white p-8 sm:p-10">
                    <p class="text-2xl font-semibold leading-tight">Paisajes Urbanos</p>
                    <p class="mt-1 text-[#ff0066] font-medium">@artista</p>
                    <span class="inline-flex items-center gap-2 mt-4 px-3 py-1 text-xs text-emerald-400 border border-brand-600 bg-white/5 rounded-full">✓ Verificado por ARTid</span>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-white/40">Año</p>
                            <p class="mt-1 font-medium">2024</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-white/40">Edición</p>
                            <p class="mt-1 font-medium">2 / 5</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-white/40">Serie</p>
                            <p class="mt-1 font-medium">Paisajes Urbanos</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-white/40">Técnica</p>
                            <p class="mt-1 font-medium">Óleo sobre lienzo</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-white/40">Dimensiones</p>
                            <p class="mt-1 font-medium">50 x 70 cm</p>
                        </div>
                    </div>
                    <div class="mt-8 flex items-center justify-between gap-6 p-5 bg-white/5 border border-white/10">
                        <div class="w-16 h-16 bg-white flex items-center justify-center">
                            <div class="grid grid-cols-5 gap-0.5">
                                @for ($i = 0; $i < 25; $i++)
                                    <div class="{{ in_array($i % 7, [0, 1, 3, 5]) ? 'bg-brand' : 'bg-white' }} w-1.5 h-1.5"></div>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm uppercase tracking-gallery text-white/50 text-right">{{ __('Ficha pública verificada') }}</p>
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

    <x-chatwoot-widget />
</body>
</html>