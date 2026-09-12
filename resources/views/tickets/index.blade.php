<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mis tickets de soporte') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif
            @if (session('ticket_number'))
                <div class="mb-4 p-4 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-md">
                    {{ __('Tu ticket es el :number. Lo revisaremos y te responderemos.', ['number' => session('ticket_number')]) }}
                </div>
            @endif

            <div class="flex justify-end mb-4">
                <a href="{{ route('tickets.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-600 transition">
                    {{ __('Nuevo ticket') }}
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($tickets->isEmpty())
                        <p class="text-gray-500 text-center py-8">{{ __('Aún no tienes tickets de soporte.') }}</p>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach ($tickets as $ticket)
                                <li class="py-4">
                                    <a href="{{ route('tickets.show', $ticket->number) }}" class="flex items-center justify-between gap-4 hover:bg-gray-50 rounded-lg -m-2 p-2">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono text-xs text-gray-400">{{ $ticket->number }}</span>
                                                <span class="px-2 py-0.5 rounded-full text-xs {{ $ticket->isClosed() ? 'bg-gray-200 text-gray-600' : 'bg-emerald-50 text-emerald-700' }}">
                                                    {{ $ticket->isClosed() ? __('Cerrado') : __('Abierto') }}
                                                </span>
                                            </div>
                                            <p class="mt-1 text-sm font-medium text-gray-900 truncate">{{ $ticket->subject }}</p>
                                            <p class="text-xs text-gray-500">{{ $ticket->topicLabel() }} · {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        @if ($ticket->attachments_count > 0)
                                            <span class="shrink-0 text-xs text-gray-400">{{ $ticket->attachments_count }} {{ $ticket->attachments_count === 1 ? __('adjunto') : __('adjuntos') }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>