<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Onboarding de artistas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Estadísticas generales --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Total artistas') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalArtists }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Completaron secuencia') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $completedCount }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Total envíos') }}</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ collect($stepStats)->sum('sent') }}</p>
                </div>
            </div>

            {{-- Botón ejecutar --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900">{{ __('Ejecutar proceso ahora') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Revisa todos los artistas y envía los emails de onboarding pendientes según las secuencias configuradas.') }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.onboarding.process') }}">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                            {{ __('Ejecutar ahora') }}
                        </button>
                    </form>
                </div>
                @if (session('status'))
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

            {{-- Detalle por step --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">{{ __('Secuencias de onboarding') }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Paso') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Días') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Condición') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Elegibles') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Enviados') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Pendientes') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($stepStats as $key => $stat)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ __($key) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $stat['delay_days'] }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $stat['condition'] }}</code>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $stat['eligible'] }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-green-600 font-medium">
                                        {{ $stat['sent'] }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-amber-600 font-medium">
                                        {{ $stat['pending'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
