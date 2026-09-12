<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Notificaciones') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif

            @if ($notifications->isNotEmpty())
                <div class="mb-4 flex justify-end">
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="text-sm text-indigo-600 hover:underline">{{ __('Marcar todas como leídas') }}</button>
                    </form>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($notifications->isEmpty())
                        <p class="text-gray-500 text-center py-8">{{ __('No tienes notificaciones.') }}</p>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach ($notifications as $notification)
                                <li class="py-4">
                                    <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left hover:bg-gray-50 rounded-lg -m-2 p-2">
                                            <div class="flex items-center gap-3">
                                                <div class="shrink-0 w-2 h-2 rounded-full {{ $notification->isRead() ? 'bg-gray-200' : 'bg-indigo-500' }}"></div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                                    @if ($notification->body)
                                                        <p class="text-xs text-gray-500 line-clamp-2">{{ $notification->body }}</p>
                                                    @endif
                                                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                                @if ($notification->url)
                                                    <span class="shrink-0 text-xs text-indigo-600">{{ __('Abrir') }} →</span>
                                                @endif
                                            </div>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>