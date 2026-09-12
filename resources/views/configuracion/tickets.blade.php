<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tickets de soporte') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif

            <div class="mb-4 flex items-center gap-2">
                <a href="{{ route('tickets.admin') }}" class="px-3 py-1.5 rounded-md text-xs font-medium uppercase tracking-wider {{ ! request('status') ? 'bg-brand text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('Todas') }}
                </a>
                <a href="{{ route('tickets.admin', ['status' => 'open']) }}" class="px-3 py-1.5 rounded-md text-xs font-medium uppercase tracking-wider {{ request('status') === 'open' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('Abiertas') }}
                </a>
                <a href="{{ route('tickets.admin', ['status' => 'closed']) }}" class="px-3 py-1.5 rounded-md text-xs font-medium uppercase tracking-wider {{ request('status') === 'closed' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('Cerradas') }}
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($tickets->isEmpty())
                        <p class="text-gray-500 text-center py-8">{{ __('No hay tickets de soporte.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-3">{{ __('Ticket') }}</th>
                                        <th class="px-4 py-3">{{ __('Artista') }}</th>
                                        <th class="px-4 py-3">{{ __('Tema') }}</th>
                                        <th class="px-4 py-3">{{ __('Asunto') }}</th>
                                        <th class="px-4 py-3">{{ __('Estado') }}</th>
                                        <th class="px-4 py-3 text-right">{{ __('Acciones') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="font-mono text-xs text-gray-400">{{ $ticket->number }}</span>
                                                <span class="block text-xs text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <p class="font-medium text-gray-900">{{ $ticket->artist?->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $ticket->artist?->email }} · <span class="font-mono">#{{ $ticket->artist_id }}</span></p>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">{{ $ticket->topicLabel() }}</td>
                                            <td class="px-4 py-3 text-gray-900 max-w-xs">
                                                <a href="{{ route('tickets.admin-show', $ticket) }}" class="hover:text-indigo-600 font-medium truncate block">{{ $ticket->subject }}</a>
                                                @if ($ticket->attachments->isNotEmpty())
                                                    <span class="text-xs text-gray-400">{{ $ticket->attachments->count() }} {{ $ticket->attachments->count() === 1 ? __('adjunto') : __('adjuntos') }}</span>
                                                @endif
                                                @if ($ticket->analysis && $ticket->analysis->isCompleted())
                                                    <span class="block text-xs text-indigo-500">{{ __('Analizado con IA') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 rounded-full text-xs {{ $ticket->isClosed() ? 'bg-gray-200 text-gray-600' : 'bg-emerald-50 text-emerald-700' }}">
                                                    {{ $ticket->isClosed() ? __('Cerrado') : __('Abierto') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                <a href="{{ route('tickets.admin-show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">{{ __('Ver') }}</a>

                                                <form method="POST" action="{{ route('tickets.admin-status', $ticket) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" name="status" value="{{ $ticket->isClosed() ? 'open' : 'closed' }}"
                                                            class="ms-2 text-{{ $ticket->isClosed() ? 'emerald-600' : 'gray-600' }} hover:underline text-sm">
                                                        {{ $ticket->isClosed() ? __('Reabrir') : __('Cerrar') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
