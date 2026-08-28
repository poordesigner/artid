<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon_192x192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon_192x192.png') }}">
    <title>{{ __('Identidad Digital para Obras de Arte') }} — ARTid</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Cormorant+Garamond:300,400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=Muli:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Muli', ui-sans-serif, system-ui, sans-serif; }
        .font-gallery { font-family: 'Cormorant Garamond', serif; }
        .tracking-gallery { letter-spacing: 0.2em; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- Navbar --}}
    <header class="border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/navbar_240x120.png') }}" alt="ARTid" class="h-9 w-auto">
                </a>
                <nav class="hidden md:flex items-center gap-10 text-sm text-gray-500">
                    <a href="{{ route('planes') }}" class="hover:text-gray-900 transition">{{ __('Planes') }}</a>
                    <a href="#galeria" class="hover:text-gray-900 transition">{{ __('Cómo funciona') }}</a>
                    <a href="{{ route('ayuda') }}" class="hover:text-gray-900 transition">{{ __('Ayuda') }}</a>
                </nav>
                <div class="flex items-center gap-5">
                    <x-language-switcher />
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900 transition">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="text-sm px-5 py-2.5 border border-gray-900 hover:bg-gray-900 hover:text-white transition">
                        {{ __('Empezar') }}
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="py-24 sm:py-32">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <p class="text-xs uppercase tracking-gallery text-gray-500">{{ __('Identidad digital para obras de arte') }}</p>
            <h1 class="mt-8 font-gallery text-5xl sm:text-6xl lg:text-7xl font-medium leading-tight">
                {{ __('El patrimonio de la obra, verificado.') }}
            </h1>
            <div class="mx-auto mt-8 h-px w-24 bg-gray-300"></div>
            <p class="mt-8 text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                {{ __('Cada obra recibe una ficha técnica permanente, un código QR firmado y un historial cifrado de propiedad. La autenticidad, a la vista de todos.') }}
            </p>
            <div class="mt-12 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3 border border-gray-900 bg-gray-900 text-white text-sm tracking-wide uppercase hover:bg-white hover:text-gray-900 transition">
                    {{ __('Empezar ahora') }}
                </a>
                <a href="{{ route('planes') }}" class="px-8 py-3 border border-gray-900 text-gray-900 text-sm tracking-wide uppercase hover:bg-gray-900 hover:text-white transition">
                    {{ __('Ver planes') }}
                </a>
            </div>
        </div>
    </section>

    {{-- Ficha destacada (museografía) --}}
    <section id="galeria" class="pb-24">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 border border-gray-200">
                {{-- Panel izquierdo: texto enmarcado --}}
                <div class="p-12 sm:p-16 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-gray-200">
                    <p class="text-xs uppercase tracking-gallery text-gray-500">{{ __('La ficha') }}</p>
                    <h2 class="mt-6 font-gallery text-3xl sm:text-4xl font-medium leading-snug">
                        {{ __('La obra completa, en una sola ficha.') }}
                    </h2>
                    <p class="mt-6 text-gray-600 leading-relaxed">
                        {{ __('Genera una ficha técnica permanente con la metadata, el historial de exposiciones y la proveniencia. Todo verificado, todo en orden.') }}
                    </p>
                    <div class="mt-8 space-y-2 text-sm text-gray-600">
                        <p>{{ __('QR permanente y firmado criptográficamente.') }}</p>
                        <p>{{ __('Historial de exposición y proveniencia.') }}</p>
                        <p>{{ __('Control de propiedad cifrado.') }}</p>
                    </div>
                </div>

                {{-- Panel derecho: placa de museo --}}
                <div class="p-12 sm:p-16 flex flex-col justify-center">
                    <div class="border border-gray-200 p-8">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-gallery text-2xl font-medium text-gray-900">Paisajes Urbanos</p>
                                <p class="mt-1 text-sm text-gray-500">Serie · Obra 2 / 5</p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">✓ Verificado</span>
                        </div>
                        <div class="mt-8 h-40 bg-gray-50 flex items-center justify-center border border-gray-100">
                            <div class="grid grid-cols-5 gap-1 opacity-70">
                                @for ($i = 0; $i < 25; $i++)
                                    <div class="{{ in_array($i % 7, [0, 1, 3, 5]) ? 'bg-gray-900' : 'bg-white' }} w-3 h-3"></div>
                                @endfor
                            </div>
                        </div>
                        <dl class="mt-8 space-y-3 text-sm">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">{{ __('Técnica') }}</dt>
                                <dd class="text-gray-900">Óleo sobre lienzo</dd>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">{{ __('Dimensiones') }}</dt>
                                <dd class="text-gray-900">50 x 70 cm</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Estado') }}</dt>
                                <dd class="text-gray-900">{{ __('Verificado') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Características / Cómo funciona --}}
    <section class="pb-24">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="flex items-end justify-between">
                <h2 class="font-gallery text-3xl sm:text-4xl font-medium">{{ __('Cómo funciona') }}</h2>
                <p class="hidden sm:block text-sm text-gray-500 max-w-xs text-right">{{ __('Tres pasos para darle identidad digital a cada obra.') }}</p>
            </div>
            <hr class="mt-6 border-gray-200">
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-12">
                <div>
                    <p class="font-gallery text-5xl font-light text-gray-300">01</p>
                    <h3 class="mt-4 text-lg font-medium tracking-wide">{{ __('Ficha de Datos') }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ __('Crea una ficha técnica completa: título, año, edición, serie, técnicas, dimensiones, descripción e imagen.') }}</p>
                </div>
                <div>
                    <p class="font-gallery text-5xl font-light text-gray-300">02</p>
                    <h3 class="mt-4 text-lg font-medium tracking-wide">{{ __('QR Permanente') }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ __('Cada obra tiene un código QR único que nunca cambia. Se imprime sobre la obra y dirige a la ficha pública.') }}</p>
                </div>
                <div>
                    <p class="font-gallery text-5xl font-light text-gray-300">03</p>
                    <h3 class="mt-4 text-lg font-medium tracking-wide">{{ __('Llaves Cifradas') }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ __('La relación entre ficha y QR está firmada criptográficamente. Solo la obra auténtica accede a su ficha.') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="border-t border-gray-200 py-24">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="font-gallery text-4xl sm:text-5xl font-medium">
                {{ __('Empezá a proteger tus obras.') }}
            </h2>
            <p class="mt-6 text-lg text-gray-600">{{ __('Creá tu cuenta y generá la identidad digital de tu primera obra en minutos.') }}</p>
            <a href="{{ route('register') }}" class="mt-10 inline-block px-10 py-3.5 border border-gray-900 bg-gray-900 text-white text-sm tracking-wide uppercase hover:bg-white hover:text-gray-900 transition">
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
                    <a href="#galeria" class="hover:text-gray-900 transition">{{ __('Cómo funciona') }}</a>
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