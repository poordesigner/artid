<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

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
                    <h3 class="font-semibold text-lg text-gray-900">{{ __('GitHub') }}</h3>

                    @if (Auth::user()->github_id)
                        <p class="mt-2 text-gray-700">
                            {{ __('Connected as') }} <span class="font-mono text-gray-900">&#64;{{ Auth::user()->github_nickname }}</span>
                        </p>
                        <p class="mt-2 text-gray-700">
                            {{ __('Repository:') }}
                            @if (Auth::user()->github_repo)
                                <span class="font-mono text-gray-900">{{ Auth::user()->github_repo }}</span>
                            @else
                                <span class="text-gray-500">{{ __('not set') }}</span>
                            @endif
                        </p>
                        <a href="{{ route('github.settings') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            {{ __('Configure repository') }}
                        </a>
                    @else
                        <p class="mt-2 text-gray-700">
                            {{ __('Connect your GitHub account to set up your artwork framework.') }}
                        </p>
                        <a href="{{ route('auth.github.redirect') }}"
                           class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Connect GitHub') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
