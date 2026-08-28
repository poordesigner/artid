<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
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

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Enlaces externos del perfil --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Enlaces del perfil') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Portafolio, CV o exposiciones. Máximo 5 enlaces.') }}</p>

                    @php $profileLinks = auth()->user()->links; @endphp

                    @if ($profileLinks->isNotEmpty())
                        <ul class="mt-6 divide-y divide-gray-100">
                            @foreach ($profileLinks as $link)
                                <li class="py-3 flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <span class="text-xs uppercase tracking-wide text-gray-500">{{ __('Enlace') }} {{ $link->type }}</span>
                                        <a href="{{ $link->url }}" target="_blank" rel="noopener" class="block text-sm text-indigo-600 hover:underline truncate">{{ $link->url }}</a>
                                    </div>
                                    <form method="POST" action="{{ route('artist-links.destroy', $link) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 text-sm text-gray-500">{{ __('No hay enlaces todavía.') }}</p>
                    @endif

                    @if ($profileLinks->count() < 5)
                        <form method="POST" action="{{ route('artist-links.store') }}" class="mt-6 flex flex-wrap items-end gap-2">
                            @csrf
                            <div class="flex flex-col">
                                <label class="text-xs text-gray-500">{{ __('Tipo') }}</label>
                                <select name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm mt-1">
                                    <option value="portfolio">{{ __('Portafolio') }}</option>
                                    <option value="cv">{{ __('CV') }}</option>
                                    <option value="exhibitions">{{ __('Exposiciones') }}</option>
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
        </div>
    </div>
</x-app-layout>
