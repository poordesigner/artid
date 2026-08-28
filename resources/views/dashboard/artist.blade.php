<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Bienvenida --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-lg">
                <h2 class="text-2xl font-bold">{{ __('Hola, :name!', ['name' => Auth::user()->name]) }}</h2>
                <p class="mt-2 text-indigo-100">{{ __('Bienvenido a tu panel de ARTid. Registra tus obras y genera su identidad digital.') }}</p>
                @if (! $canCreate)
                    <p class="mt-4 inline-flex items-center px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                        {{ __('No tienes tokens disponibles. Compra un paquete de tokens para registrar obras.') }}
                    </p>
                @endif
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Obras registradas') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $artworkCount }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Tokens') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $tokenBalance }}</p>
                    <a href="{{ route('tokens.index') }}" class="mt-1 inline-block text-sm text-indigo-600 hover:text-indigo-900">{{ __('Ver mis tokens') }} →</a>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Series') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $seriesCount }}</p>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('artworks.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:border-indigo-300 hover:shadow transition">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-gray-900">{{ __('Mis obras') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Administra tus obras, QRs y su historial.') }}</p>
                </a>

                @if ($canCreate)
                    <a href="{{ route('artworks.create') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:border-indigo-300 hover:shadow transition">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-semibold text-gray-900">{{ __('Nueva obra') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Registra una obra y genera su identidad digital.') }}</p>
                    </a>
                @else
                    <a href="{{ route('tokens.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:border-indigo-300 hover:shadow transition">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-semibold text-gray-900">{{ __('Comprar tokens') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Consigue tokens para registrar nuevas obras.') }}</p>
                    </a>
                @endif

                <a href="{{ route('series.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:border-indigo-300 hover:shadow transition">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-gray-900">{{ __('Series') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Organiza tus obras en series.') }}</p>
                </a>
            </div>

            {{-- Obras recientes --}}
            @if ($recentArtworks->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-900">{{ __('Obras recientes') }}</h3>
                        <a href="{{ route('artworks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Ver todas') }} →</a>
                    </div>
                    <ul class="mt-4 divide-y divide-gray-100">
                        @foreach ($recentArtworks as $artwork)
                            <li class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if ($artwork->image)
                                        <img src="{{ $artwork->imageUrl() }}" alt="{{ $artwork->title }}" class="h-12 w-12 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('artworks.show', $artwork) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ $artwork->title }}</a>
                                        <p class="text-xs text-gray-500 font-mono">{{ $artwork->artwork_id }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('artworks.qr', $artwork) }}" target="_blank" class="shrink-0">
                                    <img src="{{ route('artworks.qr', $artwork) }}" alt="QR" class="h-10 w-10" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>