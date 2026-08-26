<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Artwork') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-md">{{ session('error') }}</div>
                    @endif

                    @if ($atLimit)
                        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-md">
                            {{ __('Alcanzaste el límite de obras de tu plan actual. Mejora tu plan para registrar más obras.') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('artworks.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('artworks.partials.form', ['artwork' => null])

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('artworks.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ms-4" :disabled="$atLimit">
                                {{ __('Create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
