<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add ownership') }} — {{ $artwork->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-md">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('ownerships.store', $artwork) }}">
                        @csrf
                        <div>
                            <x-input-label for="type" :value="__('Type')" />
                            <select id="type" name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="initial">{{ __('Initial owner (artist)') }}</option>
                                <option value="transfer" @selected(old('type') === 'transfer')>{{ __('Transfer / Sale') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="owner_name" :value="__('Owner name')" />
                            <x-text-input id="owner_name" class="block mt-1 w-full" type="text" name="owner_name" :value="old('owner_name')" required />
                            <x-input-error :messages="$errors->get('owner_name')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="owner_email" :value="__('Owner email (optional)')" />
                            <x-text-input id="owner_email" class="block mt-1 w-full" type="email" name="owner_email" :value="old('owner_email')" />
                            <x-input-error :messages="$errors->get('owner_email')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="transferred_at" :value="__('Date')" />
                            <x-text-input id="transferred_at" class="block mt-1 w-full" type="date" name="transferred_at" :value="old('transferred_at')" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('notes') }}</textarea>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('artworks.show', $artwork) }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Add ownership') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
