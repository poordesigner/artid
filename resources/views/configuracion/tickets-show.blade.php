<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :crumbs="[
            ['label' => __('Tickets de soporte'), 'route' => route('tickets.admin')],
        ]" :current="$ticket->number" />
        <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">{{ $ticket->number }} — {{ $ticket->subject }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">
                    @if (session('status') === 'analysis-started')
                        {{ __('El gestor de tickets está analizando este ticket. Esta página se actualizará sola.') }}
                    @elseif (session('status') === 'analysis-pending')
                        {{ __('Ya hay un análisis en curso para este ticket.') }}
                    @elseif (session('status') === 'reply-sent')
                        {{ __('Respuesta enviada por email al artista.') }}
                    @elseif (session('status') === 'reply-saved-mail-failed')
                        {{ __('La respuesta quedó en el hilo pero el correo no se pudo enviar.') }}
                    @elseif (session('status') === 'reply-no-artist')
                        {{ __('No se puede responder: el artista ya no existe.') }}
                    @else
                        {{ session('status') }}
                    @endif
                </div>
            @endif

            {{-- Ticket --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-sm text-gray-400">{{ $ticket->number }}</span>
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $ticket->isClosed() ? 'bg-gray-200 text-gray-600' : 'bg-emerald-50 text-emerald-700' }}">
                            {{ $ticket->isClosed() ? __('Cerrado') : __('Abierto') }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $ticket->topicLabel() }}</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
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

            {{-- Contexto del usuario --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h3 class="text-base font-semibold text-gray-900">{{ __('Usuario') }}</h3>
                    @if ($artist)
                        <a href="{{ route('public.artist', $artist->id) }}" target="_blank"
                           class="text-sm text-indigo-600 hover:underline">{{ __('Perfil público') }} →</a>
                    @endif
                </div>

                @if ($artist)
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-400">{{ __('Identidad') }}</p>
                            <p class="font-medium text-gray-900">{{ $artist->name }}</p>
                            <p class="text-gray-500">{{ $artist->email }}
                                @if ($artist->email_verified_at)
                                    <span class="text-emerald-600">✓</span>
                                @else
                                    <span class="text-amber-600">{{ __('email no verificado') }}</span>
                                @endif
                            </p>
                            <p class="text-gray-500">#{{ $artist->id }} · {{ $artist->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-400">{{ __('Actividad') }}</p>
                            <p class="text-gray-700">
                                {{ $artist->artworks_count }} {{ $artist->artworks_count === 1 ? __('obra') : __('obras') }}
                                · {{ $artist->series_count }} {{ $artist->series_count === 1 ? __('serie') : __('series') }}
                            </p>
                            <p class="text-gray-700">
                                {{ __('Tokens:') }} <span class="font-mono">{{ $artist->tokens_balance }}</span>
                                @if (! $artist->welcome_tokens_claimed)
                                    <span class="text-gray-400">({{ __('sin welcome') }})</span>
                                @endif
                            </p>
                            @if ($artist->instagram)
                                <p class="text-gray-700">{{ $artist->instagram }}</p>
                            @endif
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-gray-400">{{ __('Antigüedad: :days días.', ['days' => $artist->created_at->diffInDays(now())]) }}</p>
                @else
                    <p class="mt-2 text-sm text-gray-500">{{ __('Usuario eliminado o no encontrado.') }}</p>
                @endif
            </div>

            {{-- Agente de tickets (gestor IA) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">{{ __('Análisis con IA') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('Resumen, prioridad sugerida, contexto y borrador de respuesta generados por el gestor de tickets.') }}</p>
                    </div>
                    @if (! $ticket->analysis || ! $ticket->analysis->isPending())
                        <form method="POST" action="{{ route('tickets.admin-analyze', $ticket) }}">
                            @csrf
                            <x-primary-button>
                                {{ $ticket->analysis ? __('Volver a analizar') : __('Analizar con IA') }}
                            </x-primary-button>
                        </form>
                    @else
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-indigo-50 text-indigo-700 text-sm">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            {{ __('Analizando…') }}
                        </span>
                    @endif
                </div>

                @php($analysis = $ticket->analysis)

                @if (! $analysis)
                    <div class="mt-4 p-4 bg-gray-50 rounded-md text-sm text-gray-500">
                        {{ __('Este ticket aún no ha sido analizado. Usa el botón para que el gestor de tickets lo procese.') }}
                    </div>
                @elseif ($analysis->isPending())
                    <div class="mt-4 p-4 bg-indigo-50 rounded-md text-sm text-indigo-700">
                        {{ __('El gestor de tickets está generando el análisis…') }}
                    </div>
                @elseif ($analysis->isFailed())
                    <div class="mt-4 p-4 bg-red-50 rounded-md text-sm text-red-700">
                        {{ __('No se pudo completar el análisis.') }}
                        @if ($analysis->error)
                            <span class="block mt-1 font-mono text-xs">{{ $analysis->error }}</span>
                        @endif
                    </div>
                @else
                    <div class="mt-4 space-y-5">
                        <div class="flex items-start gap-3">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ __('Resumen del ticket') }}</p>
                                <p class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ $analysis->summary ?: '—' }}</p>
                            </div>
                            <span class="shrink-0 px-2.5 py-1 rounded-full text-xs font-medium {{ $analysis->priority === 'alta' ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $analysis->priorityLabel() }}
                            </span>
                        </div>

                        @if ($analysis->suggested_actions)
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ __('Acciones sugeridas') }}</p>
                                <ul class="mt-1 space-y-1 list-disc list-inside text-sm text-gray-700">
                                    @foreach ($analysis->suggested_actions as $action)
                                        <li>{{ $action }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('Borrador de respuesta') }}</p>
                            <div class="mt-2 p-4 bg-gray-50 rounded-md text-sm text-gray-800 whitespace-pre-wrap border border-gray-200">
                                {{ $analysis->draft_reply ?: '—' }}
                            </div>
                        </div>

                        @if ($analysis->model || $analysis->analyzed_at)
                            <p class="text-xs text-gray-400">
                                {{ $analysis->model }} · {{ $analysis->analyzed_at?->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Hilo de respuestas --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-900">{{ __('Hilo de la conversación') }}</h3>

                @if ($ticket->replies->isEmpty())
                    <p class="mt-2 text-sm text-gray-500">{{ __('Aún no hay respuestas en este ticket.') }}</p>
                @else
                    <ul class="mt-4 space-y-4">
                        @foreach ($ticket->replies as $reply)
                            <li class="flex gap-3">
                                <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-full {{ $reply->sender === 'artist' ? 'bg-brand-100 text-brand-700' : ($reply->sender === 'agent' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700') }} text-xs font-semibold">
                                    {{ $reply->sender === 'artist' ? 'U' : ($reply->sender === 'agent' ? 'AI' : 'A') }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-xs font-medium text-gray-500">
                                            @if ($reply->sender === 'artist')
                                                {{ __('Artista') }} ({{ $ticket->artist?->name }})
                                            @elseif ($reply->sender === 'agent')
                                                {{ __('Agente IA') }}
                                            @else
                                                {{ __('Administrador') }}
                                            @endif
                                        </p>
                                        <span class="text-xs text-gray-400">{{ $reply->sent_at?->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-800 whitespace-pre-line">{{ $reply->body }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-6 border-t pt-4" x-data="{ preview: false, replyBody: @js(is_string($analysis?->draft_reply) ? $analysis->draft_reply : '') }">
                    <form method="POST" action="{{ route('tickets.admin-reply', $ticket) }}" x-ref="replyForm">
                        @csrf
                        <x-input-label for="reply_body" :value="__('Responder por email')" />
                        <textarea id="reply_body" name="body" rows="6" required x-model="replyBody"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="{{ __('Borrador de respuesta (el análisis lo pre-completa; puedes editarlo).') }}">{{ is_string($analysis?->draft_reply) ? $analysis->draft_reply : '' }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        <div class="mt-4 flex items-center gap-4">
                            <x-primary-button type="button" @click="preview = true">{{ __('Vista previa y enviar') }}</x-primary-button>
                        </div>
                    </form>

                    {{-- Modal: vista previa del correo --}}
                    <div x-show="preview" x-cloak x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div class="flex items-end sm:items-center justify-center min-h-full p-4">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="preview = false"></div>
                            <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
                                <div class="px-6 py-4 border-b">
                                    <h3 class="text-base font-semibold text-gray-900">{{ __('Vista previa del correo') }}</h3>
                                </div>
                                <div class="px-6 py-4 space-y-4 text-sm">
                                    <div class="grid grid-cols-3 gap-3 text-xs">
                                        <div class="text-gray-500">{{ __('Para') }}</div>
                                        <div class="col-span-2 font-medium text-gray-900">{{ $ticket->artist?->email }}</div>
                                        <div class="text-gray-500">{{ __('Asunto') }}</div>
                                        <div class="col-span-2 font-medium text-gray-900">{{ __('Tu ticket :number ha sido respondido', ['number' => $ticket->number]) }}</div>
                                        <div class="text-gray-500">{{ __('Ticket') }}</div>
                                        <div class="col-span-2 font-medium text-gray-900">{{ $ticket->number }}</div>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">{{ __('Mensaje') }}</p>
                                        <div class="p-4 bg-gray-50 rounded-md border border-gray-200 text-gray-800 whitespace-pre-wrap" x-text="replyBody"></div>
                                    </div>
                                    <p class="text-xs text-gray-400">{{ __('El correo se envía a nombre del soporte. El artista podrá responder desde su ticket en la plataforma; este email es solo de aviso.') }}</p>
                                </div>
                                <div class="px-6 py-4 border-t flex items-center justify-end gap-3">
                                    <button type="button" @click="preview = false" class="text-sm text-gray-600 hover:underline">{{ __('Cancelar') }}</button>
                                    <x-primary-button type="button" @click="$refs.replyForm.submit()">{{ __('Enviar por email') }}</x-primary-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('tickets.admin') }}" class="text-sm text-indigo-600 hover:underline">{{ __('Volver a tickets') }}</a>
                <form method="POST" action="{{ route('tickets.admin-status', $ticket) }}">
                    @csrf
                    <button type="submit" name="status" value="{{ $ticket->isClosed() ? 'open' : 'closed' }}"
                            class="text-sm {{ $ticket->isClosed() ? 'text-emerald-600 hover:underline' : 'text-gray-600 hover:underline' }}">
                        {{ $ticket->isClosed() ? __('Reabrir ticket') : __('Cerrar ticket') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if ($analysis && $analysis->isPending())
        <script>
            setTimeout(() => window.location.reload(), 4000);
        </script>
    @endif
</x-app-layout>