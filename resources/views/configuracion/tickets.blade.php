<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tickets de soporte') }}</h2>
    </x-slot>

    <div class="py-12" x-data="{
        active: null,
        analyses: @js($analyses),
        openIa(id) { this.active = id; },
        closeIa() { this.active = null; },
        sending: false,
        sendReply(ticketId, body) {
            this.sending = true;
            fetch('/configuracion/tickets/' + ticketId + '/reply', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ body })
            }).then(r => r.ok ? (this.closeIa(), location.reload()) : r.json().then(e => alert(e.message || 'Error')))
            .catch(() => alert('Error de red'))
            .finally(() => this.sending = false);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php($useDraftLabel = __('Usar borrador sugerido...'))
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

                                                @if ($ticket->analysis && $ticket->analysis->isCompleted())
                                                    <button type="button" x-on:click="openIa({{ $ticket->id }})"
                                                            class="ms-2 text-indigo-600 hover:text-indigo-900 text-sm">{{ __('Revisar y Enviar') }}</button>
                                                @endif

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

        {{-- Modal: análisis del agente IA + hilo + respuesta --}}
        <template x-if="active">
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-end sm:items-center justify-center min-h-full p-4">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-on:click="closeIa()"></div>
                    <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                        <div class="px-6 py-4 border-b flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900" x-text="analyses[active]?.number + ' — ' + analyses[active]?.subject"></h3>
                                <span class="mt-1 inline-block px-2 py-0.5 rounded-full text-xs font-medium"
                                      :class="analyses[active]?.priority === 'alta' ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-600'"
                                      x-text="analyses[active]?.priority_label"></span>
                            </div>
                            <button type="button" x-on:click="closeIa()" class="text-gray-400 hover:text-gray-600">✕</button>
                        </div>
                        <div class="px-6 py-4 space-y-4 text-sm overflow-y-auto flex-1" x-show="active">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-400">{{ __('Resumen del ticket') }}</p>
                                <p class="mt-1 text-gray-800 whitespace-pre-line" x-text="analyses[active]?.summary"></p>
                            </div>
                            <div x-show="analyses[active]?.suggested_actions?.length">
                                <p class="text-xs uppercase tracking-wider text-gray-400">{{ __('Acciones sugeridas') }}</p>
                                <ul class="mt-1 space-y-1 list-disc list-inside text-gray-700">
                                    <template x-for="action in analyses[active]?.suggested_actions" :key="action">
                                        <li x-text="action"></li>
                                    </template>
                                </ul>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-gray-400">{{ __('Borrador sugerido') }}</p>
                                <div class="mt-1 p-4 bg-gray-50 rounded-md border border-gray-200 text-gray-800 whitespace-pre-wrap" x-text="analyses[active]?.draft_reply || '—'"></div>
                            </div>
                            <div class="border-t pt-4">
                                <p class="text-xs uppercase tracking-wider text-gray-400">{{ __('Historial de conversación') }}</p>
                                <div class="mt-2 space-y-3 max-h-64 overflow-y-auto">
                                    <template x-for="reply in analyses[active]?.replies" :key="reply.sent_at + reply.sender">
                                        <div class="flex" :class="reply.sender === 'admin' ? 'justify-end' : 'justify-start'">
                                            <div class="max-w-[75%] p-3 rounded-lg"
                                                 :class="reply.sender === 'admin' ? 'bg-brand text-white rounded-br-none' : 'bg-gray-100 text-gray-900 rounded-bl-none'">
                                                <p class="text-sm whitespace-pre-wrap" x-text="reply.body"></p>
                                                <p class="text-xs mt-1 opacity-70 text-right"
                                                   x-text="reply.sent_at ? new Date(reply.sent_at).toLocaleString() : ''"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="!analyses[active]?.replies?.length" class="text-center text-gray-400 text-sm py-4">
                                        {{ __('Sin respuestas aún') }}
                                    </div>
                                </div>
                            </div>
                            <div x-show="analyses[active]?.ticket_status === 'open'" class="border-t pt-4">
                                <p class="text-xs uppercase tracking-wider text-gray-400">{{ __('Enviar respuesta') }}</p>
                                <textarea x-ref="replyBody" class="mt-2 w-full p-3 border border-gray-300 rounded-md text-sm resize-none"
                                        rows="4" :placeholder="analyses[active]?.draft_reply ? '{{ $useDraftLabel }}' : ''"
                                        x-init="if ($refs.replyBody && analyses[active]?.draft_reply) $refs.replyBody.value = analyses[active].draft_reply"></textarea>
                                <div class="mt-2 flex justify-end gap-2">
                                    <button type="button" @click="closeIa()" class="px-4 py-2 text-sm text-gray-600 hover:underline">{{ __('Cancelar') }}</button>
                                    <button type="button" @click="sendReply(active, $refs.replyBody.value)"
                                            :disabled="sending" class="px-4 py-2 bg-brand text-white rounded-md text-sm hover:bg-brand-600 disabled:opacity-50">
                                        <span x-show="!sending">{{ __('Enviar respuesta') }}</span>
                                        <span x-show="sending">{{ __('Enviando...') }}</span>
                                    </button>
                                </div>
                            </div>
                            <div x-show="analyses[active]?.ticket_status === 'closed'" class="border-t pt-4 text-center text-gray-500 text-sm py-2">
                                {{ __('Ticket cerrado. Reabre para responder.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>