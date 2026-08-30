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

            <a href="{{ route('tickets.index') }}" class="text-sm text-indigo-600 hover:underline">{{ __('Volver a mis tickets') }}</a>
        </div>
    </div>
</x-app-layout>