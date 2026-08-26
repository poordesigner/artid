<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :crumbs="[
            ['label' => __('Mi Plan'), 'route' => route('configuracion', ['tab' => 'mi-plan'])],
        ]" :current="__('Cambiar plan')" />
        <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirmar cambio de plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Resumen del cambio') }}</h3>
                <p class="mt-1 text-sm text-gray-600">{{ __('Revisá los montos antes de confirmar tu cambio de plan.') }}</p>

                <div class="mt-6 space-y-4">
                    {{-- De --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ __('Plan actual') }}</p>
                            <p class="font-medium text-gray-900">{{ $currentPlan?->name }} · {{ $subscription->period?->recurrenceLabel() }}</p>
                        </div>
                        <span class="text-lg font-bold text-gray-900">${{ number_format($subscription->period?->price ?? 0, 2) }}</span>
                    </div>

                    {{-- Flecha --}}
                    <div class="flex justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>

                    {{-- A --}}
                    <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-lg">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ __('Plan nuevo') }}</p>
                            <p class="font-medium text-gray-900">{{ $targetPlan?->name }} · {{ $newPeriod->recurrenceLabel() }}</p>
                        </div>
                        <span class="text-lg font-bold text-gray-900">${{ number_format($newPeriod->price, 2) }}</span>
                    </div>
                </div>

                {{-- Prorrateo --}}
                <div class="mt-6 border-t border-gray-100 pt-6">
                    {{-- Rango del periodo --}}
                    <div class="rounded-lg bg-gray-50 p-4 text-sm">
                        <p class="font-medium text-gray-900">{{ __('Período actual') }}</p>
                        <p class="mt-1 text-gray-600">
                            {{ __('Inicio') }}: <strong>{{ $proration['period_start'] }}</strong> ·
                            {{ __('Fin') }}: <strong>{{ $proration['period_end'] }}</strong> ·
                            {{ __('Hoy') }}: <strong>{{ $proration['today'] }}</strong>
                        </p>
                        <div class="mt-2 flex items-center gap-3 text-xs text-gray-600">
                            <span class="inline-flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                {{ __('Usado') }}: {{ $proration['used_days'] }} días
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                {{ __('Restante') }}: {{ $proration['rest_days'] }} días
                            </span>
                            <span class="text-gray-400">({{ $proration['total_days'] }} total)</span>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-gray-200 overflow-hidden">
                            <div class="h-full bg-emerald-500" style="width: {{ $proration['total_days'] > 0 ? round($proration['used_days'] / $proration['total_days'] * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-600">{{ __('Crédito por el tiempo no usado') }}</dt>
                            <dd class="font-medium text-gray-900">${{ $amounts['credit'] }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-600">{{ __('Cargo por el nuevo plan') }}</dt>
                            <dd class="font-medium text-gray-900">${{ $amounts['charge'] }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                            <dt class="font-semibold text-gray-900">
                                @if ($amounts['action'] === 'charge')
                                    {{ __('Total a cobrar ahora') }}
                                @elseif ($amounts['action'] === 'credit')
                                    {{ __('Crédito a favor') }}
                                @else
                                    {{ __('Total') }}
                                @endif
                            </dt>
                            <dd class="text-lg font-bold {{ $amounts['action'] === 'charge' ? 'text-gray-900' : 'text-emerald-600' }}">
                                ${{ $amounts['to_action'] }}
                            </dd>
                        </div>
                    </dl>

                    <p class="mt-4 text-xs text-gray-500">
                        {{ __('El prorrateo se calcula al minuto. Si hay un monto a cobrar, se cargará automáticamente a tu método de pago guardado en Paddle.') }}
                    </p>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('configuracion', ['tab' => 'mi-plan']) }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ __('Cancelar') }}
                    </a>
                    @if ($amounts['action'] === 'charge' || $amounts['action'] === 'none')
                        <form method="POST" action="{{ route('subscribe.change', $newPeriod) }}">
                            @csrf
                            <x-primary-button>
                                {{ __('Confirmar y cobrar $:amount', ['amount' => $amounts['to_action']]) }}
                            </x-primary-button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('subscribe.change', $newPeriod) }}">
                            @csrf
                            <x-primary-button class="!bg-emerald-600 hover:!bg-emerald-700">
                                {{ __('Confirmar (sin cargo)') }}
                            </x-primary-button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>