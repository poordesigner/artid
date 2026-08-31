<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :crumbs="[
            ['label' => __('Mis tickets de soporte'), 'route' => route('tickets.index')],
        ]" :current="$ticket->number" />
        <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">{{ $ticket->number }} — {{ $ticket->subject }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-sm text-gray-400">{{ $ticket->number }}</span>
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $ticket->isClosed() ? 'bg-gray-200 text-gray-600' : 'bg-emerald-50 text-emerald-700' }}">
                            {{ $ticket->isClosed() ? __('Cerrado') : __('Abierto') }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $ticket->topicLabel() }} · {{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <p class="mt-4 text-sm text-gray-800 whitespace-pre-line">{{ $ticket->message }}</p>

                @if ($ticket->attachments->isNotEmpty())
                    <div class="mt-6 border-t pt-4">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('Adjuntos') }}</h3>
                        <ul class="mt-2 space-y-1">
                            @foreach ($ticket->attachments as $attachment)
                                <li>
                                    <a href="{{ route('tickets.attachment', [$ticket->number, $attachment]) }}"
                                       class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:underline">
                                        <span>📎</span>
                                        <span class="truncate">{{ $attachment->original_name }}</span>
                                        <span class="text-xs text-gray-400">({{ $attachment->humanSize() }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            @if ($ticket->replies->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-900">{{ __('Hilo de la conversación') }}</h3>
                    <ul class="mt-4 space-y-4">
                        @foreach ($ticket->replies as $reply)
                            <li class="flex gap-3">
                                <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-full {{ $reply->sender === 'artist' ? 'bg-brand-100 text-brand-700' : ($reply->sender === 'agent' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700') }} text-xs font-semibold">
                                    {{ $reply->sender === 'artist' ? 'Tú' : ($reply->sender === 'agent' ? 'AI' : 'A') }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-xs font-medium text-gray-500">
                                            @if ($reply->sender === 'artist')
                                                {{ __('Tú') }}
                                            @elseif ($reply->sender === 'agent')
                                                {{ __('Agente IA') }}
                                            @else
                                                {{ __('Soporte') }}
                                            @endif
                                        </p>
                                        <span class="text-xs text-gray-400">{{ $reply->sent_at?->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-800 whitespace-pre-line">{{ $reply->body }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (! $ticket->isClosed())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-900">{{ __('Añadir un mensaje') }}</h3>
                    <form method="POST" action="{{ route('tickets.reply', $ticket->number) }}" class="mt-3">
                        @csrf
                        <textarea name="body" rows="5" required
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="{{ __('Escribe aquí tu respuesta para el soporte…') }}"></textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        <div class="mt-4">
                            <x-primary-button>{{ __('Enviar mensaje') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            @endif

            <a href="{{ route('tickets.index') }}" class="text-sm text-indigo-600 hover:underline">{{ __('Volver a mis tickets') }}</a>
        </div>
    </div>
</x-app-layout>