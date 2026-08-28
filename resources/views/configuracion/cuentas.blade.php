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
                <p class="mt-1 text-sm text-gray-600">{{ __('Otorga tokens gratuitos y gestiona administradores.') }}</p>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Artista') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tokens') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Obras') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Admin') }}</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($artists as $artist)
                                <tr class="align-top">
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $artist->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $artist->email }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-semibold text-gray-900">{{ $artist->tokens_balance }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $artist->artworks_count }}
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
                                            <summary class="text-sm text-indigo-600 hover:text-indigo-900 cursor-pointer">{{ __('Otorgar tokens') }}</summary>
                                            <form method="POST" action="{{ route('accounts.grant', $artist) }}" class="mt-2 flex items-center gap-2 justify-end">
                                                @csrf
                                                <input type="number" name="token_amount" min="1" required placeholder="{{ __('Cantidad') }}"
                                                    class="w-24 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                                <x-primary-button>{{ __('Otorgar') }}</x-primary-button>
                                            </form>
                                        </details>

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