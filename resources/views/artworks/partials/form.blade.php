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
        <img src="{{ route('artworks.image', $artwork) }}" alt="{{ $artwork->title }}" class="mt-2 h-40 object-contain rounded border border-gray-200" />
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
<div class="mt-4">
    <x-input-label for="status" :value="__('Status')" />
    <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
        @foreach (\App\Models\Artwork::STATUSES as $status)
            <option value="{{ $status }}" @selected(old('status', $artwork->status ?? 'created') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
</div>

<!-- Series -->
<div class="mt-4">
    <x-input-label for="series" :value="__('Series')" />
    <x-text-input id="series" class="block mt-1 w-full" type="text" name="series" :value="old('series', $artwork->series ?? '')" />
    <x-input-error :messages="$errors->get('series')" class="mt-2" />
</div>

<!-- Technique -->
<div class="mt-4">
    <x-input-label for="technique" :value="__('Technique')" />
    <x-text-input id="technique" class="block mt-1 w-full" type="text" name="technique" :value="old('technique', $artwork->technique ?? '')" />
    <x-input-error :messages="$errors->get('technique')" class="mt-2" />
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

<!-- Location -->
<div class="mt-4">
    <x-input-label for="location" :value="__('Location')" />
    <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $artwork->location ?? '')" />
    <x-input-error :messages="$errors->get('location')" class="mt-2" />
</div>

<!-- Owner -->
<div class="mt-4">
    <x-input-label for="owner" :value="__('Owner')" />
    <x-text-input id="owner" class="block mt-1 w-full" type="text" name="owner" :value="old('owner', $artwork->owner ?? '')" />
    <x-input-error :messages="$errors->get('owner')" class="mt-2" />
</div>
