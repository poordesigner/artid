<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis tokens') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('status'))
                <div class="p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-50 text-red-700 rounded-md">{{ session('error') }}</div>
            @endif

            {{-- Saldo --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Tokens disponibles') }}</p>
                        <p class="mt-1 text-6xl font-bold text-gray-900">{{ $balance }}</p>
                    </div>
                    <a href="#paquetes" class="shrink-0 px-6 py-3 bg-gray-900 text-white text-sm uppercase tracking-wider hover:bg-gray-700 transition">
                        {{ __('Comprar tokens') }}
                    </a>
                </div>
            </div>

            {{-- Paquetes + usos (misma dinámica que planes) --}}
            @if ($packages->count())
                <div id="paquetes" class="grid grid-cols-1 lg:grid-cols-[280px_1fr_320px] gap-8"
                     x-data="packageSelector(@js($packages->map(fn ($p) => [
                         'id' => $p->id,
                         'name' => $p->name,
                         'tokens' => $p->tokens,
                         'price' => (float) $p->price_usd,
                         'perToken' => round((float) $p->price_usd / $p->tokens, 2),
                         'url' => route('tokens.checkout', $p->id),
                     ])->all()))">

                    {{-- Columna 1: lista de paquetes --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ __('Paquetes') }}</p>
                        <div class="mt-3 space-y-3">
                            @foreach ($packages as $package)
                                <button type="button" @mouseenter="selected = {{ $package->id }}"
                                    @focus="selected = {{ $package->id }}"
                                    :class="selected === {{ $package->id }} ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-200 bg-white text-gray-900 hover:border-gray-400'"
                                    class="w-full flex items-center justify-between px-5 py-4 border rounded-lg text-left transition">
                                    <span class="font-semibold">{{ $package->name }}</span>
                                    <span class="text-sm">
                                        ${{ number_format($package->price_usd, 0) }} USD
                                    </span>
                                    @if ($loop->first)
                                        <span class="ml-2 shrink-0 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider rounded-full bg-indigo-100 text-indigo-700">
                                            {{ __('Popular') }}
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Columna 2: detalle del paquete seleccionado + comprar --}}
                    <div class="flex flex-col justify-center">
                        <div class="border border-gray-200 p-8" x-cloak>
                            <template x-for="p in packages" :key="p.id">
                                <div x-show="selected === p.id">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500" x-text="p.name"></p>
                                    <p class="mt-4 text-6xl font-bold text-gray-900 leading-none">
                                        <span x-text="p.tokens"></span>
                                        <span class="text-2xl font-medium text-gray-500">{{ __('tokens') }}</span>
                                    </p>
                                    <p class="mt-4 text-xl text-gray-600">
                                        <span x-text="'$' + formatUsd(p.perToken)"></span>
                                        <span class="text-sm">{{ __('USD/token') }}</span>
                                    </p>
                                    <p class="mt-2 text-3xl font-semibold text-gray-900">
                                        <span x-text="'$' + formatUsd(p.price)"></span>
                                        <span class="text-base text-gray-500">USD</span>
                                    </p>
                                    <form :action="p.url" method="POST" class="mt-8">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-full px-6 py-4 bg-gray-900 hover:bg-gray-700 text-white text-sm font-semibold uppercase tracking-wider transition">
                                            {{ __('Comprar') }}
                                        </button>
                                    </form>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Columna 3: usos de tokens --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ __('Usos de tokens') }}</p>
                        <p class="mt-2 text-sm text-gray-600">{{ __('Cada función consume la cantidad de tokens indicada.') }}</p>
                        <div class="mt-4 space-y-3">
                            @forelse ($tokenFunctions as $tf)
                                <div class="border border-gray-200 rounded-lg px-5 py-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-sm font-medium text-gray-900">{{ $tf->name }}</span>
                                        <span class="text-sm font-semibold text-gray-700">
                                            <span class="text-lg font-bold">{{ $tf->tokens }}</span>
                                            {{ $tf->tokens === 1 ? __('token') : __('tokens') }}
                                        </span>
                                    </div>
                                    @if ($tf->actions->isNotEmpty())
                                        <div class="mt-2 flex flex-wrap gap-1.5">
                                            @foreach ($tf->actions as $action)
                                                <span class="px-2 py-0.5 text-[10px] bg-gray-100 text-gray-600 rounded-full">{{ $action->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">{{ __('No hay funciones definidas.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <p class="text-gray-500">{{ __('No hay planes disponibles.') }}</p>
                </div>
            @endif

            {{-- Historial --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h3 class="font-semibold text-lg text-gray-900">{{ __('Historial de tokens') }}</h3>
                    @if ($transactions->isEmpty())
                        <p class="mt-4 text-sm text-gray-500">{{ __('Aún no hay movimientos de tokens.') }}</p>
                    @else
                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-3">{{ __('Fecha') }}</th>
                                        <th class="px-4 py-3">{{ __('Concepto') }}</th>
                                        <th class="px-4 py-3">{{ __('Cantidad') }}</th>
                                        <th class="px-4 py-3">{{ __('Saldo') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @foreach ($transactions as $tx)
                                        <tr>
                                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $tx->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-4 py-3 text-gray-900">
                                                @if ($tx->type === 'purchase')
                                                    {{ __('Compra de paquete') }}
                                                @elseif ($tx->type === 'grant')
                                                    {{ __('Token otorgado') }}
                                                @else
                                                    {{ __('Consumo por obra') }}
                                                @endif
                                                @if ($tx->note)
                                                    <span class="block text-xs text-gray-400">{{ $tx->note }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 font-medium {{ $tx->amount < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                                {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">{{ $tx->balance_after }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>