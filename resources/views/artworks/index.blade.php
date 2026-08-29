<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-end gap-3 mb-4">
                        <a href="{{ route('series.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Series') }}
                        </a>
                        @if ($artist->currentMaxArtworks() !== null && $artist->activeArtworksCount() >= $artist->currentMaxArtworks())
                            <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 rounded-md text-xs uppercase tracking-widest cursor-not-allowed">{{ __('Límite alcanzado') }}</span>
                        @else
                            <a href="{{ route('artworks.create') }}" class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-600 focus:bg-brand-600 active:bg-brand focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('New Artwork') }}
                            </a>
                        @endif
                    </div>

                    @php
                        $max = $artist->currentMaxArtworks();
                        $count = $artist->activeArtworksCount();
                    @endphp
                    @if ($max !== null)
                        <p class="mb-4 text-sm text-gray-600">
                            {{ __('Obras registradas') }}: <strong>{{ $count }} / {{ $max }}</strong>
                        </p>
                    @endif

                    {{-- Filtros y orden --}}
                    <div class="mb-4 flex flex-wrap items-center gap-2">
                        <a href="{{ route('artworks.index', ['status' => 'all', 'sort' => $sort]) }}"
                           class="px-3 py-1.5 rounded-md text-xs font-medium uppercase tracking-wider transition
                                  {{ $status === 'all' ? 'bg-brand text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ __('Todas') }}
                        </a>
                        <a href="{{ route('artworks.index', ['status' => 'active', 'sort' => $sort]) }}"
                           class="px-3 py-1.5 rounded-md text-xs font-medium uppercase tracking-wider transition
                                  {{ $status === 'active' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ __('Activas') }}
                        </a>
                        <a href="{{ route('artworks.index', ['status' => 'inactive', 'sort' => $sort]) }}"
                           class="px-3 py-1.5 rounded-md text-xs font-medium uppercase tracking-wider transition
                                  {{ $status === 'inactive' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ __('Inactivas') }}
                        </a>
                        <span class="mx-1 text-gray-300">|</span>
                        <select onchange="window.location.href = this.value" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-xs font-medium text-gray-700">
                            <option value="{{ route('artworks.index', ['status' => $status, 'sort' => 'recent']) }}" @selected($sort === 'recent')>{{ __('Más recientes') }}</option>
                            <option value="{{ route('artworks.index', ['status' => $status, 'sort' => 'oldest']) }}" @selected($sort === 'oldest')>{{ __('Más antiguas') }}</option>
                            <option value="{{ route('artworks.index', ['status' => $status, 'sort' => 'title']) }}" @selected($sort === 'title')>{{ __('Título A-Z') }}</option>
                        </select>
                        @if ($status === 'inactive' || $status === 'all')
                            <span class="ml-auto text-xs text-gray-400">{{ __('Las obras inactivas se muestran atenuadas.') }}</span>
                        @endif
                    </div>

                    @if ($artworks->isEmpty())
                        <p class="text-gray-500 text-center py-8">
                            @if ($status === 'inactive')
                                {{ __('No hay obras inactivas.') }}
                            @elseif ($status === 'active')
                                {{ __('No hay obras activas.') }}
                            @else
                                {{ __('No artworks yet.') }}
                            @endif
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full w-full table-fixed divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="w-20 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('QR') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                                        <th class="w-16 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Año') }}</th>
                                        <th class="w-28 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Registrada') }}</th>
                                        <th class="w-24 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th class="w-48 px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($artworks as $artwork)
                                        @php $inactive = $artwork->status === 'archived'; @endphp
                                        <tr class="{{ $inactive ? 'bg-gray-50 opacity-60' : '' }}">
                                            <td class="px-4 py-3">
                                                <a href="{{ route('artworks.qr', $artwork) }}" target="_blank" title="{{ $artwork->artwork_id }}">
                                                    <img src="{{ route('artworks.qr', $artwork) }}" alt="QR {{ $artwork->artwork_id }}" class="h-12 w-12 object-contain" />
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <a href="{{ route('artworks.show', $artwork) }}" class="hover:text-indigo-600 font-medium">{{ $artwork->title }}</a>
                                                <span class="block font-mono text-xs text-gray-400 truncate">{{ $artwork->artwork_id }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $artwork->year ?? '—' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $artwork->created_at->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                @if ($inactive)
                                                    <span class="px-2 py-0.5 bg-gray-200 text-gray-600 rounded-full text-xs font-medium">{{ __('Inactiva') }}</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">{{ __('Activa') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                                                <a href="{{ route('exhibitions.create', $artwork) }}" class="text-gray-600 hover:text-gray-900">{{ __('+ Expo') }}</a>
                                                <a href="{{ route('ownerships.create', $artwork) }}" class="ms-2 text-gray-600 hover:text-gray-900">{{ __('+ Propiedad') }}</a>
                                                <a href="{{ route('artworks.edit', $artwork) }}" class="ms-2 text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                                <form method="POST" action="{{ route('artworks.destroy', $artwork) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ms-2 text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $artworks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
