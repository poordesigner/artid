<!-- Title -->
<div>
    <x-input-label for="title" :value="__('Title')" />
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $artwork->title ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
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
