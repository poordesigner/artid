<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Perfil del artista') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Información pública que se muestra en la ficha de tus obras.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Avatar -->
        <div>
            <x-input-label for="avatar" :value="__('Foto de perfil')" />
            @if ($user->avatar)
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="mt-2 h-20 w-20 rounded-full object-cover border border-gray-200" />
            @endif
            <input id="avatar" name="avatar" type="file" accept="image/*" class="block mt-2 w-full text-sm text-gray-700 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            <p class="mt-1 text-xs text-gray-500">{{ __('Foto cuadrada recomendada. Máximo 5 MB.') }}</p>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Statement -->
        <div>
            <x-input-label for="statement" :value="__('Declaración / Artist Statement')" />
            <textarea id="statement" name="statement" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('statement', $user->statement ?? '') }}</textarea>
            <p class="mt-1 text-xs text-gray-500">{{ __('Breve declaración sobre tu práctica artística. Máximo 5000 caracteres.') }}</p>
            <x-input-error class="mt-2" :messages="$errors->get('statement')" />
        </div>

        <!-- CV PDF -->
        <div>
            <x-input-label for="cv_pdf" :value="__('Hoja de vida (PDF)')" />
            @if ($user->cv_pdf)
                <a href="{{ $user->cvUrl() }}" target="_blank" class="mt-1 inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    {{ __('Ver CV actual') }}
                </a>
            @endif
            <input id="cv_pdf" name="cv_pdf" type="file" accept=".pdf" class="block mt-2 w-full text-sm text-gray-700 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            <p class="mt-1 text-xs text-gray-500">{{ __('Solo PDF. Máximo 10 MB.') }}</p>
            <x-input-error class="mt-2" :messages="$errors->get('cv_pdf')" />
        </div>

        <!-- Website -->
        <div>
            <x-input-label for="website_url" :value="__('Página web')" />
            <x-text-input id="website_url" name="website_url" type="url" class="mt-1 block w-full" :value="old('website_url', $user->website_url ?? '')" placeholder="https://..." />
            <x-input-error class="mt-2" :messages="$errors->get('website_url')" />
        </div>

        <!-- Social Networks -->
        <div class="space-y-4">
            <h3 class="text-sm font-medium text-gray-900">{{ __('Redes sociales') }}</h3>
            <p class="text-xs text-gray-500">{{ __('Solo el nombre de usuario (sin la URL completa).') }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="instagram" :value="__('Instagram')" />
                    <x-text-input id="instagram" name="instagram" type="text" class="mt-1 block w-full" :value="old('instagram', $user->instagram ?? '')" placeholder="@usuario" />
                    <x-input-error class="mt-2" :messages="$errors->get('instagram')" />
                </div>
                <div>
                    <x-input-label for="behance" :value="__('Behance')" />
                    <x-text-input id="behance" name="behance" type="text" class="mt-1 block w-full" :value="old('behance', $user->behance ?? '')" placeholder="usuario" />
                    <x-input-error class="mt-2" :messages="$errors->get('behance')" />
                </div>
                <div>
                    <x-input-label for="artstation" :value="__('ArtStation')" />
                    <x-text-input id="artstation" name="artstation" type="text" class="mt-1 block w-full" :value="old('artstation', $user->artstation ?? '')" placeholder="usuario" />
                    <x-input-error class="mt-2" :messages="$errors->get('artstation')" />
                </div>
                <div>
                    <x-input-label for="youtube" :value="__('YouTube')" />
                    <x-text-input id="youtube" name="youtube" type="text" class="mt-1 block w-full" :value="old('youtube', $user->youtube ?? '')" placeholder="@usuario" />
                    <x-input-error class="mt-2" :messages="$errors->get('youtube')" />
                </div>
                <div>
                    <x-input-label for="tiktok" :value="__('TikTok')" />
                    <x-text-input id="tiktok" name="tiktok" type="text" class="mt-1 block w-full" :value="old('tiktok', $user->tiktok ?? '')" placeholder="@usuario" />
                    <x-input-error class="mt-2" :messages="$errors->get('tiktok')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
