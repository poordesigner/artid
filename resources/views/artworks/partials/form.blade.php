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
    <x-text-input id="artwork_id" class="block mt-1 w-full" type="text" name="artwork_id" :value="old('artwork_id')" placeholder="Ej: NATURAI-3.0" />
    <x-input-error :messages="$errors->get('artwork_id')" class="mt-2" />
    <p class="mt-1 text-xs text-gray-500">{{ __('Uppercase, dashes or dots. If empty, it is generated from the title.') }}</p>
</div>
@endif

<!-- Image -->
<div class="mt-4">
    <x-input-label for="image" :value="__('Image')" />
    <input id="image" name="image" type="file" accept="image/*" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full text-sm text-gray-700" />
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
    @if (isset($artwork) && $artwork && $artwork->image)
        <img src="{{ $artwork->imageUrl() }}" alt="{{ $artwork->title }}" class="mt-2 h-40 object-contain rounded border border-gray-200" />
        <p class="mt-1 text-xs text-gray-500">{{ __('Current image:') }} <span class="font-mono">{{ $artwork->image }}</span></p>
    @endif
</div>

<!-- Year -->
<div class="mt-4">
    <x-input-label for="year" :value="__('Year')" />
    <x-text-input id="year" class="block mt-1 w-full" type="text" name="year" :value="old('year', $artwork->year ?? '')" />
    <x-input-error :messages="$errors->get('year')" class="mt-2" />
</div>

<!-- Edition -->
<div class="mt-4">
    <x-input-label for="edition" :value="__('Edition')" />
    <x-text-input id="edition" class="block mt-1 w-full" type="text" name="edition" :value="old('edition', $artwork->edition ?? '')" />
    <x-input-error :messages="$errors->get('edition')" class="mt-2" />
</div>

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
    @php
        $selectedTechniques = old('techniques', []);
        if (empty($selectedTechniques) && isset($artwork) && $artwork && $artwork->technique) {
            $selectedTechniques = array_map('trim', explode(',', $artwork->technique));
        }
    @endphp
    <div class="mt-2 max-h-56 overflow-y-auto border border-gray-300 rounded-md p-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
        @foreach ($techniques as $technique)
            <label class="flex items-start gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="techniques[]" value="{{ $technique->name }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mt-0.5"
                    @if (in_array($technique->name, $selectedTechniques)) checked @endif>
                <span>
                    <span class="font-medium">{{ $technique->name }}</span>
                    @if ($technique->description)
                        <span class="block text-xs text-gray-500">{{ $technique->description }}</span>
                    @endif
                </span>
            </label>
        @endforeach
    </div>
    <x-input-error :messages="$errors->get('techniques')" class="mt-2" />
</div>

<!-- Dimensions -->
<div class="mt-4">
    <x-input-label for="dimensions" :value="__('Dimensions')" />
    <x-text-input id="dimensions" class="block mt-1 w-full" type="text" name="dimensions" :value="old('dimensions', $artwork->dimensions ?? '')" />
    <x-input-error :messages="$errors->get('dimensions')" class="mt-2" />
</div>

<!-- Description -->
<div class="mt-4">
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $artwork->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>
