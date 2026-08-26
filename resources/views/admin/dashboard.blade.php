<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Estadísticas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Artistas') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['artists'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Planes de pago') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['paid_plans'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Suscripciones activas') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['active_subscriptions'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Cobrado (USD)') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">${{ number_format($stats['total_paid'], 2) }}</p>
                </div>
            </div>

            {{-- Paneles de gestión --}}
            <div class="mt-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">{{ __('Gestión') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('accounts.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:border-indigo-300 hover:shadow transition">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h4 class="mt-4 font-semibold text-gray-900">{{ __('Gestión de cuentas') }}</h4>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Otorga planes, revoca accesos, gestiona administradores y revisa cada artista.') }}</p>
                        <span class="mt-3 inline-block text-sm font-medium text-indigo-600">{{ __('Abrir panel') }} →</span>
                    </a>

                    <a href="{{ route('plans.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:border-indigo-300 hover:shadow transition">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="mt-4 font-semibold text-gray-900">{{ __('Gestión de planes') }}</h4>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Crea y edita los planes de suscripción, sus períodos y límites.') }}</p>
                        <span class="mt-3 inline-block text-sm font-medium text-indigo-600">{{ __('Abrir panel') }} →</span>
                    </a>

                    <a href="{{ route('configuracion') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:border-indigo-300 hover:shadow transition">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                        </div>
                        <h4 class="mt-4 font-semibold text-gray-900">{{ __('Configuración') }}</h4>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Seguridad de tu cuenta, email y contraseña.') }}</p>
                        <span class="mt-3 inline-block text-sm font-medium text-indigo-600">{{ __('Abrir panel') }} →</span>
                    </a>
                </div>
            </div>

            {{-- Artistas recientes --}}
            @if ($recentArtists->isNotEmpty())
                <div class="mt-6 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-900">{{ __('Artistas recientes') }}</h3>
                    <ul class="mt-4 divide-y divide-gray-100">
                        @foreach ($recentArtists as $artist)
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $artist->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $artist->email }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $artist->created_at->format('d/m/Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>