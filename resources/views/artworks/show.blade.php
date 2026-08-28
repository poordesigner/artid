<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :crumbs="[
            ['label' => __('Obras'), 'route' => route('artworks.index')],
        ]" :current="$artwork->title" />
        <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">
            {{ $artwork->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-md">{{ session('error') }}</div>
            @endif

            @if (session('secret_key'))
                <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-md">
                    <p class="text-sm font-semibold text-amber-800">{{ __('Llave secreta del nuevo propietario (guárdala, solo se muestra una vez):') }}</p>
                    <code class="mt-1 block text-xl font-mono text-amber-900">{{ session('secret_key') }}</code>
                </div>
            @endif

            @if (session('revealed'))
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <p class="text-sm font-semibold text-blue-800">{{ __('Propietario revelado:') }}</p>
                    <p class="mt-1 text-gray-900">{{ session('revealed')['name'] }}</p>
                    @if (session('revealed')['email'])
                        <p class="text-gray-700">{{ session('revealed')['email'] }}</p>
                    @endif
                    @if (session('revealed')['date'])
                        <p class="text-gray-600">{{ session('revealed')['date'] }}</p>
                    @endif
                </div>
            @endif

            <!-- Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col sm:flex-row gap-6">
                    @if ($artwork->image)
                        <div class="shrink-0">
                            <img src="{{ $artwork->imageUrl() }}" alt="{{ $artwork->title }}" class="h-48 object-contain rounded border border-gray-200" />
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-lg font-semibold text-gray-900">{{ $artwork->title }}</p>
                        <p class="font-mono text-sm text-gray-500">{{ $artwork->artwork_id }}</p>
                        <dl class="mt-3 text-sm text-gray-700 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @if ($artwork->year)
                                <div><dt class="font-medium text-gray-500">{{ __('Year') }}</dt><dd>{{ $artwork->year }}</dd></div>
                            @endif
                            @if ($artwork->edition)
                                <div><dt class="font-medium text-gray-500">{{ __('Edition') }}</dt><dd>{{ $artwork->edition }}</dd></div>
                            @endif
                            @if ($artwork->series)
                                <div><dt class="font-medium text-gray-500">{{ __('Series') }}</dt><dd>{{ $artwork->series }}</dd></div>
                            @endif
                            @if ($artwork->technique)
                                <div><dt class="font-medium text-gray-500">{{ __('Technique') }}</dt><dd>{{ $artwork->technique }}</dd></div>
                            @endif
                            @if ($artwork->dimensions)
                                <div><dt class="font-medium text-gray-500">{{ __('Dimensions') }}</dt><dd>{{ $artwork->dimensions }}</dd></div>
                            @endif
                        </dl>
                        @if ($artwork->description)
                            <p class="mt-3 text-sm text-gray-700">{{ $artwork->description }}</p>
                        @endif
                    </div>
                    <div class="shrink-0 text-center">
                        <a href="{{ route('artworks.qr', $artwork) }}" target="_blank">
                            <img src="{{ route('artworks.qr', $artwork) }}" alt="QR" class="h-28 w-28 inline-block" />
                        </a>
                    </div>
                </div>
            </div>

            <!-- Exhibitions -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-900">{{ __('Exhibitions') }}</h3>
                        <a href="{{ route('exhibitions.create', $artwork) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">{{ __('+ Add exhibition') }}</a>
                    </div>

                    @if ($artwork->exhibitions->isEmpty())
                        <p class="mt-4 text-sm text-gray-500">{{ __('No exhibitions yet.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach ($artwork->exhibitions as $exhibition)
                                <li class="py-3 flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $exhibition->name }}</p>
                                        @if ($exhibition->start_date)
                                            <p class="text-xs text-gray-500">{{ __('Fecha de inicio') }}: {{ $exhibition->start_date->format('Y-m-d') }}</p>
                                        @endif
                                        @if ($exhibition->end_date)
                                            <p class="text-xs text-gray-500">{{ __('Fecha de fin') }}: {{ $exhibition->end_date->format('Y-m-d') }}</p>
                                        @endif
                                        @if ($exhibition->location)
                                            <p class="text-xs text-gray-500">{{ __('Ubicación') }}: {{ $exhibition->location }}</p>
                                        @endif
                                        @if ($exhibition->description)<p class="text-sm text-gray-600">{{ $exhibition->description }}</p>@endif
                                        @if ($exhibition->links)<p class="text-xs text-indigo-600 truncate">{{ $exhibition->links }}</p>@endif
                                    </div>
                                    <form method="POST" action="{{ route('exhibitions.destroy', $exhibition) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Links externos -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-900">{{ __('Enlaces externos') }}</h3>
                        <span class="text-xs text-gray-500">{{ $artwork->links->count() }}/10</span>
                    </div>

                    @if ($artwork->links->isEmpty())
                        <p class="mt-4 text-sm text-gray-500">{{ __('No hay enlaces todavía.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach ($artwork->links as $link)
                                <li class="py-2 flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <span class="text-xs uppercase tracking-wide text-gray-500">{{ __('Enlace') }} {{ $link->type }}</span>
                                        <a href="{{ $link->url }}" target="_blank" rel="noopener" class="block text-sm text-indigo-600 hover:underline truncate">{{ $link->url }}</a>
                                    </div>
                                    <form method="POST" action="{{ route('artwork-links.destroy', $link) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($artwork->links->count() < 10)
                        <form method="POST" action="{{ route('artwork-links.store', $artwork) }}" class="mt-4 flex flex-wrap items-end gap-2">
                            @csrf
                            <div class="flex flex-col">
                                <label class="text-xs text-gray-500">{{ __('Tipo') }}</label>
                                <select name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm mt-1">
                                    <option value="video">{{ __('Video') }}</option>
                                    <option value="photo">{{ __('Foto') }}</option>
                                    <option value="blog">{{ __('Blog') }}</option>
                                </select>
                            </div>
                            <div class="flex-1 min-w-[16rem] flex flex-col">
                                <label class="text-xs text-gray-500">{{ __('URL') }}</label>
                                <x-text-input type="url" name="url" placeholder="https://..." class="block mt-1 w-full" required />
                            </div>
                            <x-secondary-button>{{ __('Agregar enlace') }}</x-secondary-button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Ownership -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-900">{{ __('Ownership / Provenance') }}</h3>
                        <a href="{{ route('ownerships.create', $artwork) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">{{ __('+ Add ownership') }}</a>
                    </div>

                    @if ($artwork->ownerships->isEmpty())
                        <p class="mt-4 text-sm text-gray-500">{{ __('No ownership records yet.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach ($artwork->ownerships as $ownership)
                                <li class="py-3">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <span class="text-xs uppercase tracking-wide text-gray-500">{{ $ownership->type === 'transfer' ? __('Transfer') : __('Initial') }}</span>
                                            @if ($ownership->type === 'transfer')
                                                <p class="text-sm text-gray-700">{{ __('🔒 Propietario protegido') }}</p>
                                            @else
                                                <p class="text-sm text-gray-900">{{ $ownership->owner_name }}</p>
                                                @if ($ownership->owner_email)<p class="text-xs text-gray-500">{{ $ownership->owner_email }}</p>@endif
                                            @endif
                                            @if ($ownership->transferred_at)<p class="text-xs text-gray-500">{{ $ownership->transferred_at->format('Y-m-d') }}</p>@endif
                                            @if ($ownership->notes)<p class="text-xs text-gray-500">{{ $ownership->notes }}</p>@endif
                                        </div>
                                        <form method="POST" action="{{ route('ownerships.destroy', $ownership) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                    @if ($ownership->type === 'transfer')
                                        <form method="POST" action="{{ route('ownerships.reveal', $ownership) }}" class="mt-2 flex items-center gap-2">
                                            @csrf
                                            <x-text-input type="text" name="secret_key" placeholder="{{ __('Secret key') }}" class="block w-64" />
                                            <x-secondary-button>{{ __('Reveal') }}</x-secondary-button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
