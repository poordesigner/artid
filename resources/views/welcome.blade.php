<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ARTid — Identidad Digital para Obras de Arte</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-white">

    {{-- Header --}}
    <header class="border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="ARTid" class="h-10 w-auto">
                </a>
                <a href="{{ route('ayuda') }}" class="text-sm text-gray-500 hover:text-gray-700">{{ __('Ayuda') }}</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Iniciar sesión') }}
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Registrarse') }}
                </a>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">
                Identidad Digital para tus Obras de Arte
            </h1>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                Generá una ficha técnica permanente para cada obra. Código QR único, metadata verificada y control de propiedad cifrado.
            </p>
            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Empezar ahora') }}
                </a>
                <a href="{{ route('ayuda') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Ver guía') }}
                </a>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Ficha de Datos</h3>
                    <p class="mt-2 text-sm text-gray-600">Creá una ficha técnica completa: título, año, edición, serie, técnicas, dimensiones, descripción e imagen.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">QR Permanente</h3>
                    <p class="mt-2 text-sm text-gray-600">Cada obra tiene un código QR único que nunca cambia. Se imprime sobre la obra y dirige a la ficha pública.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Llaves Cifradas</h3>
                    <p class="mt-2 text-sm text-gray-600">La relación entre ficha y QR está firmada criptográficamente. Solo la obra auténtica accede a su ficha.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Plans --}}
    @if ($plans->count())
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">{{ __('Planes de Suscripción') }}</h2>
                    <p class="mt-2 text-gray-600">{{ __('Elegí el plan que mejor se adapte a tus necesidades.') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-{{ min($plans->count(), 3) }} gap-8 max-w-5xl mx-auto">
                    @foreach ($plans as $plan)
                        @php
                            $monthlyPeriod = $plan->periods->firstWhere('period', 'monthly');
                            $price = $monthlyPeriod ? $monthlyPeriod->price : null;
                        @endphp
                        <div class="bg-white rounded-2xl shadow-sm border {{ $loop->first ? 'border-indigo-500 ring-2 ring-indigo-500' : 'border-gray-200' }} p-8 flex flex-col">
                            @if ($loop->first)
                                <span class="self-start px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full uppercase tracking-wider">{{ __('Popular') }}</span>
                            @endif

                            <h3 class="text-xl font-bold text-gray-900 mt-{{ $loop->first ? '4' : '0' }}">{{ $plan->name }}</h3>

                            @if ($plan->description)
                                <p class="mt-2 text-sm text-gray-600">{{ $plan->description }}</p>
                            @endif

                            <div class="mt-6">
                                @if ($price !== null)
                                    <span class="text-4xl font-bold text-gray-900">${{ number_format($price, 2) }}</span>
                                    <span class="text-gray-500">/ {{ __('mes') }}</span>
                                @else
                                    <span class="text-4xl font-bold text-gray-900">—</span>
                                @endif
                            </div>

                            {{-- Períodos --}}
                            @if ($plan->periods->count() > 1)
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach ($plan->periods->where('period', '!=', 'monthly') as $period)
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">
                                            {{ $period->number }} {{ $period->period_label }} · ${{ number_format($period->price, 2) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Características --}}
                            @if ($plan->features->count())
                                <ul class="mt-6 space-y-3 flex-1">
                                    @foreach ($plan->features as $feature)
                                        <li class="flex items-start gap-3 text-sm text-gray-700">
                                            <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $feature->description }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ route('register') }}" class="mt-8 block text-center px-6 py-3 {{ $loop->first ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-800 hover:bg-gray-700 text-white' }} rounded-md font-semibold text-sm uppercase tracking-wider transition ease-in-out duration-150">
                                {{ __('Empezar') }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Footer --}}
    <footer class="py-8 bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} <a href="https://poordesigner.com" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">POORdesigner.com</a>. {{ __('Todos los derechos reservados.') }}
            </p>
        </div>
    </footer>
</body>
</html>
