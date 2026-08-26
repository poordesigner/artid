<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de cuentas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-50 text-red-700 rounded-md">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-900">{{ __('Cuentas de artistas') }}</h3>
                <p class="mt-1 text-sm text-gray-600">{{ __('Otorga planes, revoca accesos y gestiona administradores.') }}</p>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Artista') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Plan efectivo') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Obras') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Admin') }}</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($artists as $artist)
                                @php
                                    $effective = $artist->effectivePlan();
                                    $granted = $artist->grantedPlan();
                                @endphp
                                <tr class="align-top">
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $artist->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $artist->email }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm {{ $granted ? 'text-indigo-600 font-medium' : 'text-gray-700' }}">
                                            {{ $effective?->name ?? '—' }}
                                            @if ($granted)
                                                <span class="ml-1 px-1.5 py-0.5 text-[10px] bg-indigo-50 text-indigo-700 rounded-full font-semibold">{{ __('otorgado') }}</span>
                                            @endif
                                        </p>
                                        @if ($granted?->id !== $effective?->id && $artist->activeSubscription)
                                            <p class="text-xs text-gray-500">{{ __('Suscripción: :plan', ['plan' => $artist->activeSubscription->plan?->name]) }}</p>
                                        @endif
                                        @if ($artist->granted_expires_at)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ __('Vence el :date', ['date' => $artist->granted_expires_at->format('d/m/Y')]) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $artist->artworks_count }}
                                        @if ($effective?->max_artworks)
                                            <span class="text-gray-400">/ {{ $effective->max_artworks }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($artist->is_admin)
                                            <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-700 rounded-full font-medium">{{ __('Sí') }}</span>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <details class="inline">
                                            <summary class="text-sm text-indigo-600 hover:text-indigo-900 cursor-pointer">{{ __('Otorgar plan') }}</summary>
                                            <form method="POST" action="{{ route('accounts.grant', $artist) }}" class="mt-2 flex items-center gap-2 justify-end">
                                                @csrf
                                                <select name="plan_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                                    @foreach ($plans as $plan)
                                                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                                    @endforeach
                                                </select>
                                                <select name="duration" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                                    <option value="30">{{ __('30 días') }}</option>
                                                    <option value="7">{{ __('7 días') }}</option>
                                                    <option value="90">{{ __('90 días') }}</option>
                                                    <option value="none">{{ __('Sin expiración') }}</option>
                                                </select>
                                                <x-primary-button>{{ __('Otorgar') }}</x-primary-button>
                                            </form>
                                        </details>

                                        @if ($granted)
                                            <form method="POST" action="{{ route('accounts.revoke', $artist) }}" class="inline mt-1">
                                                @csrf
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Revocar') }}</button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('accounts.toggle-admin', $artist) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm {{ $artist->is_admin ? 'text-amber-600 hover:text-amber-900' : 'text-gray-500 hover:text-gray-900' }}">
                                                {{ $artist->is_admin ? __('Quitar admin') : __('Hacer admin') }}
                                            </button>
                                        </form>
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