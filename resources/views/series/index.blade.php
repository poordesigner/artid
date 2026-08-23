<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Series') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900">{{ __('New Series') }}</h3>
                    <form method="POST" action="{{ route('series.store') }}" class="mt-3">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="2" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="mt-4 flex justify-end">
                            <x-primary-button>{{ __('Create') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($series->isEmpty())
                        <p class="text-gray-500 text-center py-4">{{ __('No series yet.') }}</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($series as $item)
                                <li class="py-3 flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                                        @if ($item->description)
                                            <p class="text-sm text-gray-500">{{ $item->description }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ $item->artworks_count }} {{ __('obras') }}</p>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0">
                                        <button type="button" onclick="document.getElementById('edit-{{ $item->id }}').classList.toggle('hidden')" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                        <form method="POST" action="{{ route('series.destroy', $item) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </li>
                                <li id="edit-{{ $item->id }}" class="hidden py-3 border-t border-gray-100">
                                    <form method="POST" action="{{ route('series.update', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <x-input-label for="name-{{ $item->id }}" :value="__('Name')" />
                                            <x-text-input id="name-{{ $item->id }}" class="block mt-1 w-full" type="text" name="name" :value="$item->name" required />
                                        </div>
                                        <div class="mt-2">
                                            <x-input-label for="description-{{ $item->id }}" :value="__('Description')" />
                                            <textarea id="description-{{ $item->id }}" name="description" rows="2" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ $item->description }}</textarea>
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <x-secondary-button>{{ __('Save') }}</x-secondary-button>
                                        </div>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
