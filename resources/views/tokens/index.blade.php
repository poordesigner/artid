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
                        <p class="mt-2 text-sm text-gray-500">{{ __('1 token = QR + ficha básica de una obra, para siempre.') }}</p>
                    </div>
                    <a href="#paquetes" class="shrink-0 px-6 py-3 bg-gray-900 text-white text-sm uppercase tracking-wider hover:bg-gray-700 transition">
                        {{ __('Comprar tokens') }}
                    </a>
                </div>
            </div>

            {{-- Paquetes --}}
            <div id="paquetes" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h3 class="font-semibold text-lg text-gray-900">{{ __('Paquetes de tokens') }}</h3>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($packages as $package)
                            <div class="border border-gray-200 rounded-lg p-6 flex flex-col">
                                <p class="text-xs uppercase tracking-wider text-gray-500">{{ $package->name }}</p>
                                <p class="mt-3 text-3xl font-bold text-gray-900">{{ $package->tokens }} <span class="text-base font-normal text-gray-500">{{ __('tokens') }}</span></p>
                                <p class="mt-2 text-2xl font-medium text-gray-700">${{ number_format($package->price_usd, 2) }} USD</p>
                                @if ($package->description)
                                    <p class="mt-3 text-sm text-gray-500">{{ $package->description }}</p>
                                @endif
                                <form method="POST" action="{{ route('tokens.checkout', $package) }}" class="mt-6">
                                    @csrf
                                    <button type="submit" class="w-full px-5 py-3 border border-gray-900 text-gray-900 text-sm uppercase tracking-wider hover:bg-gray-900 hover:text-white transition">
                                        {{ __('Comprar') }}
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

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