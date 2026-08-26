<!-- Title -->
<div>
    <x-input-label for="title" :value="__('Title')" />
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $artwork->title ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

@if (! isset($artwork) || ! $artwork)
<!-- Artwork ID (create only, permanent) -->
<div class="mt-4">
    <x-input-label for="artwork_id" :value="__('Artwork ID (optional)')" />
    <x-text-input id="artwork_id" class="block mt-1 w-full" type="text" name="artwork_id" :value="old('artwork_id')" placeholder="Ej: MI-OBRA-01" />
    <x-input-error :messages="$errors->get('artwork_id')" class="mt-2" />
    <p class="mt-1 text-xs text-gray-500">{{ __('Solo mayúsculas, guiones o puntos (ej. MI-OBRA-01, NATURAI-3.0). Es permanente y único. Si lo dejas vacío, se genera del título.') }}</p>
</div>
@endif

<!-- Image -->
<div class="mt-4">
    <x-input-label for="image" :value="__('Image')" />
    <input id="image" name="image" type="file" accept="image/*" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full text-sm text-gray-700" />
    <p class="mt-1 text-xs text-gray-500">{{ __('Imagen JPG o PNG, máximo 2 MB.') }}</p>
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
    @if (isset($artwork) && $artwork && $artwork->image)
        <img src="{{ $artwork->imageUrl() }}" alt="{{ $artwork->title }}" class="mt-2 h-40 object-contain rounded border border-gray-200" />
        <p class="mt-1 text-xs text-gray-500">{{ __('Current image:') }} <span class="font-mono">{{ $artwork->image }}</span></p>
    @endif
</div>

<!-- Year -->
<div class="mt-4">
    <x-input-label for="year" :value="__('Year')" />
    <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" min="1000" :max="date('Y') + 1" :value="old('year', $artwork->year ?? '')" placeholder="Ej: 2025" />
    <x-input-error :messages="$errors->get('year')" class="mt-2" />
</div>

<!-- Edition -->
<div class="mt-4" x-data="editionPicker()">
    <x-input-label for="edition_type" :value="__('Edición')" />
    <select id="edition_type" name="edition_type" x-model="type" @change="updateEdition()" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
        <option value="single">{{ __('Pieza única') }}</option>
        <option value="edition">{{ __('Tiraje') }}</option>
        <option value="pa">{{ __('P/A (Prueba de artista)') }}</option>
    </select>

    <div x-show="type === 'edition'" x-transition class="mt-3">
        <x-input-label for="edition_copies" :value="__('Nº de copias')" />
        <x-text-input id="edition_copies" class="block mt-1 w-full" type="number" x-model="copies" min="1" @input="updateEdition()" :value="old('edition_copies')" />
    </div>

    <input type="hidden" name="edition" :value="editionValue">
    <input type="hidden" name="edition_type_value" :value="type">
    <x-input-error :messages="$errors->get('edition')" class="mt-2" />
</div>
<script>
    function editionPicker() {
        const existing = '{{ old('edition', $artwork->edition ?? '') }}';
        let type = 'single';
        let copies = 1;

        if (existing.startsWith('P/A')) { type = 'pa'; }
        else if (existing.includes('/')) { type = 'edition'; copies = existing.split('/')[1] || 1; }

        function build() {
            if (type === 'pa') return 'P/A';
            if (type === 'edition') return '1/' + copies;
            return '';
        }

        return {
            type,
            copies,
            editionValue: build(),
            updateEdition() { this.editionValue = build(); },
        };
    }
</script>

<!-- Status -->
<!-- (oculto por ahora: se deriva del historial) -->

<!-- Series -->
<div class="mt-4">
    <x-input-label for="series_id" :value="__('Series')" />
    <select id="series_id" name="series_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
        <option value="">{{ __('None') }}</option>
        @foreach ($seriesList as $item)
            <option value="{{ $item->id }}" @selected((int) old('series_id', $artwork->series_id ?? 0) === $item->id)>{{ $item->name }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('series_id')" class="mt-2" />
</div>

<!-- Technique -->
<div class="mt-4">
    <x-input-label :value="__('Technique')" />
    <p class="mt-1 text-xs text-gray-500">{{ __('Selecciona una o más técnicas. Escribe para filtrar y usa el botón de borrar para quitar.') }}</p>

{{-- técnicas seleccionadas (edición) --}}
@php
    $selectedTechniques = old('techniques', []);
    if (empty($selectedTechniques) && isset($artwork) && $artwork && $artwork->technique) {
        $selectedTechniques = array_map('trim', explode(',', $artwork->technique));
    }
@endphp
<script type="application/json" id="technique-options">
    {!! json_encode($techniques->map(fn ($t) => ['value' => $t->name, 'name' => $t->name, 'description' => $t->description])->values()->all(), JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/json" id="technique-selected">
    {!! json_encode($selectedTechniques) !!}
</script>
<div x-data="techniquePicker(JSON.parse(document.getElementById('technique-selected').textContent), JSON.parse(document.getElementById('technique-options').textContent))"
     class="mt-2">
    <div class="flex flex-wrap items-center gap-2">
        <template x-for="tag in selected" :key="tag">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs">
                <span x-text="tag"></span>
                <button type="button" @click="remove(tag)" class="hover:text-indigo-600" title="{{ __('Quitar') }}">&times;</button>
            </span>
        </template>

        <input type="text"
               x-model="search"
               @focus="open = true; $el.focus()"
               @blur="setTimeout(() => open = false, 200)"
               @keydown-Backspace="onKeydown"
               @keydown-Escape="open = false"
               class="flex-1 min-w-[14rem] border border-gray-300 rounded-md shadow-sm text-sm text-gray-900 px-2 py-1 outline-none focus:ring-indigo-500 focus:border-indigo-500"
               placeholder="{{ __('Buscar técnica...') }}">
    </div>

    <div x-show="open"
         x-transition
         class="mt-1 max-h-60 overflow-y-auto border border-gray-300 rounded-md bg-white shadow-lg z-10">
        <template x-if="filtered.length === 0">
            <p class="p-2 text-sm text-gray-500">{{ __('Sin resultados') }}</p>
        </template>
        <template x-for="option in filtered" :key="option.value">
            <div class="px-3 py-1.5 cursor-pointer hover:bg-indigo-50"
                 @mousedown.prevent="toggle(option)">
                <div class="flex items-center gap-2">
                    <input type="checkbox" :value="option.value" :checked="selected.includes(option.value)" class="pointer-events-none rounded border-gray-300 text-indigo-600">
                    <div>
                        <span class="text-sm text-gray-900" x-text="option.name"></span>
                        <template x-if="option.description">
                            <p class="text-xs text-gray-500" x-text="option.description"></p>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

        <!-- hidden inputs that mirror the selected techniques on submit -->
        <template x-for="tag in selected" :key="tag">
            <input type="hidden" name="techniques[]" :value="tag">
        </template>
    </div>
    <x-input-error :messages="$errors->get('techniques')" class="mt-2" />
</div>

<!-- Dimensions -->
<div class="mt-4" x-data="dimensionsPicker()">
    <x-input-label :value="__('Dimensiones (alto x ancho x profundidad)')" />
    <div class="mt-1 grid grid-cols-3 gap-3">
        <div>
            <x-input-label for="dim_height" :value="__('Alto')" />
            <x-text-input id="dim_height" class="block mt-1 w-full" type="number" step="0.1" x-model="height" @input="updateDimensions()" placeholder="0" />
        </div>
        <div>
            <x-input-label for="dim_width" :value="__('Ancho')" />
            <x-text-input id="dim_width" class="block mt-1 w-full" type="number" step="0.1" x-model="width" @input="updateDimensions()" placeholder="0" />
        </div>
        <div>
            <x-input-label for="dim_depth" :value="__('Profundidad')" />
            <x-text-input id="dim_depth" class="block mt-1 w-full" type="number" step="0.1" x-model="depth" @input="updateDimensions()" placeholder="0" />
        </div>
    </div>
    <div class="mt-3">
        <x-input-label for="dim_unit" :value="__('Unidad')" />
        <select id="dim_unit" name="dim_unit" x-model="unit" @change="updateDimensions()" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
            <option value="cm" selected>cm</option>
            <option value="m">m</option>
            <option value="in">in</option>
        </select>
    </div>
    <input type="hidden" name="dimensions" :value="dimensionsValue">
    <x-input-error :messages="$errors->get('dimensions')" class="mt-2" />
    <p class="mt-1 text-xs text-gray-500" x-text="dimensionsValue || '{{ __('Sin dimensiones') }}'"></p>
</div>
<script>
    function dimensionsPicker() {
        const existing = '{{ old('dimensions', $artwork->dimensions ?? '') }}';
        const parts = existing ? existing.split(' x ') : [];
        const height = parseFloat(parts[0]) || '';
        const width = parseFloat(parts[1]) || '';
        const depth = parseFloat(parts[2]) || '';
        let unit = parts[3] || 'cm';

        return {
            height, width, depth, unit,
            dimensionsValue: existing,
            updateDimensions() {
                if (this.height === '' && this.width === '' && this.depth === '') {
                    this.dimensionsValue = '';
                    return;
                }
                this.dimensionsValue = `${this.height || 0} x ${this.width || 0} x ${this.depth || 0} ${this.unit}`.trim();
            },
        };
    }
</script>

<!-- Description -->
<div class="mt-4">
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4" maxlength="500" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" x-data x-init="let $t = $el; $el.addEventListener('input', () => { document.getElementById('desc-count').textContent = $t.value.length + ' / 500'; });">{{ old('description', $artwork->description ?? '') }}</textarea>
    <div class="mt-1 flex justify-between">
        <span class="text-xs text-gray-500">{{ __('Máximo 500 caracteres.') }}</span>
        <span id="desc-count" class="text-xs text-gray-400">0 / 500</span>
    </div>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>
