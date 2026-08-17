<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artworks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('artworks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('New Artwork') }}
                        </a>
                    </div>

                    @if ($artworks->isEmpty())
                        <p class="text-gray-500 text-center py-8">{{ __('No artworks yet.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('QR') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Year') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($artworks as $artwork)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <a href="{{ route('artworks.qr', $artwork) }}" target="_blank" title="{{ $artwork->artwork_id }}">
                                                    <img src="{{ route('artworks.qr', $artwork) }}" alt="QR {{ $artwork->artwork_id }}" class="h-16 w-16 object-contain" />
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <a href="{{ route('artworks.show', $artwork) }}" class="text-gray-900 hover:text-indigo-600 font-medium">{{ $artwork->title }}</a>
                                                <span class="block font-mono text-xs text-gray-400">{{ $artwork->artwork_id }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $artwork->year ?? '—' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($artwork->status) }}</td>
                                            <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                                                <a href="{{ route('artworks.edit', $artwork) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                                <form method="POST" action="{{ route('artworks.destroy', $artwork) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ms-3 text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
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
