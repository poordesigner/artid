<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :crumbs="[
            ['label' => __('Mis tickets de soporte'), 'route' => route('tickets.index')],
        ]" :current="__('Nuevo ticket')" />
        <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">{{ __('Nuevo ticket de soporte') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data"
                  class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                @csrf

                <div>
                    <x-input-label for="topic" :value="__('Tema')" />
                    <select id="topic" name="topic" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        @foreach (\App\Models\SupportTicket::TOPICS as $topic)
                            <option value="{{ $topic }}" @selected(old('topic') === $topic)>{{ __(\App\Models\SupportTicket::TOPICS_LABELS[$topic] ?? $topic) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('topic')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="subject" :value="__('Asunto')" />
                    <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" :value="old('subject')" required maxlength="255" />
                    <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="message" :value="__('Mensaje')" />
                    <textarea id="message" name="message" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('message') }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">{{ __('Máximo 5000 caracteres.') }}</p>
                    <x-input-error :messages="$errors->get('message')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="attachments" :value="__('Archivos adjuntos (opcional)')" />
                    <input id="attachments" name="attachments[]" type="file" multiple
                           accept="image/jpeg,image/png,image/webp,image/gif,application/pdf"
                           class="mt-1 block w-full text-sm text-gray-700 border-gray-300 rounded-md shadow-sm" />
                    <p class="mt-1 text-xs text-gray-400">{{ __('JPG/PNG/WebP/GIF o PDF. Máximo 5 MB por archivo y hasta 3 archivos.') }}</p>
                    <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
                    <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('tickets.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900">{{ __('Cancelar') }}</a>
                    <x-primary-button>{{ __('Crear ticket') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>