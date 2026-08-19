<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-md">{{ session('error') }}</div>
            @endif

            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900">{{ __('GitHub') }}</h3>
                    @if (Auth::user()->github_id)
                        <p class="mt-2 text-sm text-gray-700">
                            {{ __('Conectado como') }} <span class="font-mono font-semibold">&#64;{{ Auth::user()->github_nickname }}</span>
                        </p>
                    @else
                        <p class="mt-2 text-sm text-gray-600">{{ __('Conecta tu cuenta de GitHub para vincular tu repositorio.') }}</p>
                        <a href="{{ route('auth.github.redirect') }}"
                           class="mt-3 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Connect GitHub') }}
                        </a>
                    @endif
                </div>
            </div>

            @if (Auth::user()->github_repo)
                <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between gap-4">
                    <p class="text-sm text-gray-700">
                        {{ __('Current repository:') }}
                        <span class="font-mono font-semibold text-gray-900">{{ Auth::user()->github_repo }}</span>
                    </p>
                    <div class="flex items-center gap-4">
                        <form method="POST" action="{{ route('github.sync-ficha') }}">
                            @csrf
                            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                                {{ __('Install ficha') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('github.sync') }}">
                            @csrf
                            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                                {{ __('Sync artworks') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900">{{ __('Link an existing repository') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Choose one of your GitHub repositories to store your artwork framework.') }}</p>

                    @if (empty($repos))
                        <p class="mt-4 text-sm text-gray-500">{{ __('No repositories found.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200 border border-gray-200 rounded-lg">
                            @foreach ($repos as $repo)
                                <li class="flex items-center justify-between px-4 py-3">
                                    <span class="font-mono text-sm text-gray-900">{{ $repo['full_name'] }}</span>
                                    <form method="POST" action="{{ route('github.link') }}">
                                        @csrf
                                        <input type="hidden" name="repo" value="{{ $repo['full_name'] }}">
                                        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                                            {{ __('Link') }}
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900">{{ __('Short URL (short.io)') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Tu dominio corto (sin https://). El QR codificará https://<dominio>?art=<ID>.') }}</p>

                    <form method="POST" action="{{ route('github.short-domain') }}" class="mt-4">
                        @csrf
                        <div>
                            <x-input-label for="short_domain" :value="__('Short domain')" />
                            <x-text-input id="short_domain" class="block mt-1 w-full" type="text" name="short_domain" :value="old('short_domain', Auth::user()->short_domain)" placeholder="tatomico.s.gy" />
                            <x-input-error :messages="$errors->get('short_domain')" class="mt-2" />
                        </div>

                        <div class="mt-4 flex justify-end">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900">{{ __('Create a new repository') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __('ARTid will create a new repository in your GitHub account.') }}</p>

                    <form method="POST" action="{{ route('github.create') }}" class="mt-4">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Repository name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4 flex justify-end">
                            <x-primary-button>{{ __('Create') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
