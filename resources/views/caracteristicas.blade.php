<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon_192x192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon_192x192.png') }}">
    <title>{{ __('Características') }} — QRTE</title>
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

    <section class="py-24 sm:py-28">
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
                    <span class="inline-flex items-center gap-2 mt-4 px-3 py-1 text-xs text-emerald-400 border border-brand-600 bg-white/5 rounded-full">✓ Verificado por QRTE</span>
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

    @include('partials.public-footer')

    <x-chatwoot-widget />
</body>
</html>