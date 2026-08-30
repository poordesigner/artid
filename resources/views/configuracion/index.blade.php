<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración') }}
        </h2>
    </x-slot>

    @php
    $initialTab = request('tab', 'seguridad');
    $allowedTabs = ['seguridad'];
    if (! $user->isAdmin()) {
        $allowedTabs[] = 'mi-plan';
    } else {
        $allowedTabs[] = 'planes';
        $allowedTabs[] = 'packages';
        $allowedTabs[] = 'token-functions';
        $allowedTabs[] = 'ia';
    }
    if (! in_array($initialTab, $allowedTabs)) {
        $initialTab = 'seguridad';
    }
@endphp
<div class="py-12 config-compact">
        <style>
            .config-compact .text-xs { font-size: 0.60rem; }
            .config-compact .text-sm { font-size: 0.70rem; }
            .config-compact .text-base { font-size: 0.80rem; }
            .config-compact .text-lg { font-size: 0.90rem; }
            .config-compact .text-xl { font-size: 1.00rem; }
            .config-compact .text-2xl { font-size: 1.10rem; }
            .config-compact .text-3xl { font-size: 1.30rem; }
            .config-compact .text-4xl { font-size: 1.50rem; }
            .config-compact .text-5xl { font-size: 1.80rem; }
            .config-compact .text-6xl { font-size: 2.10rem; }
            .config-compact .text-7xl { font-size: 2.40rem; }
            .config-compact .text-8xl { font-size: 2.80rem; }
        </style>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: '{{ $initialTab }}', portalLoading: false, openPortal() {
            this.portalLoading = true;
            fetch('{{ route('subscribe.portal') }}', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    this.portalLoading = false;
                    if (data.url) {
                        window.open(data.url, '_blank');
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(() => { this.portalLoading = false; });
        } }">
            {{-- Tabs --}}
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex gap-6">
                    <button @click="tab = 'seguridad'" :class="tab === 'seguridad' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        {{ __('Seguridad') }}
                    </button>
                    @if (! $user->isAdmin())
                        <button @click="tab = 'mi-plan'" :class="tab === 'mi-plan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            {{ __('Mis tokens') }}
                        </button>
                    @else
                        <button @click="tab = 'planes'" :class="tab === 'planes' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            {{ __('Planes') }}
                        </button>
                        <button @click="tab = 'packages'" :class="tab === 'packages' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            {{ __('Paquetes de tokens') }}
                        </button>
                        <button @click="tab = 'token-functions'" :class="tab === 'token-functions' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            {{ __('Usos de tokens') }}
                        </button>
                        <button @click="tab = 'ia'" :class="tab === 'ia' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            {{ __('IA') }}
                        </button>
                    @endif
                </nav>
            </div>

            {{-- Tab: Seguridad --}}
            <div x-show="tab === 'seguridad'" x-transition>
                <div class="space-y-6">
                    <!-- Email -->
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-gray-900">
                                        {{ __('Email y contraseña') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ __('Gestiona tu email y contraseña.') }}
                                    </p>
                                </header>

                                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                                    @csrf
                                    @method('patch')

                                    <div>
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                    </div>

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div>
                                            <p class="text-sm text-gray-800">
                                                {{ __('Tu email no está verificado.') }}
                                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    {{ __('Reenviar email de verificación.') }}
                                                </button>
                                            </p>
                                        </div>
                                    @endif

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

                                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                    @csrf
                                </form>
                            </section>
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Cuenta -->
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Mi Plan --}}
            <div x-show="tab === 'mi-plan'" x-transition>
                <div class="space-y-6">
                    {{-- Saldo de tokens --}}
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-gray-900">
                                        {{ __('Mis tokens') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ __('1 token = QR + ficha básica de una obra, para siempre.') }}
                                    </p>
                                </header>
                                <div class="mt-4">
                                    <p class="text-5xl font-bold text-gray-900">{{ $user->tokens_balance }}</p>
                                    <div class="mt-4 flex flex-wrap gap-3">
                                        <a href="{{ route('tokens.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm hover:bg-indigo-700 transition">
                                            {{ __('Comprar tokens') }}
                                        </a>
                                        <a href="{{ route('tokens.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md font-semibold text-sm hover:bg-gray-50 transition">
                                            {{ __('Ver historial') }}
                                        </a>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Planes (admin) --}}
            @if ($user->isAdmin())
                <div x-show="tab === 'planes'" x-transition>
                    <div x-data="planForm()">
                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900">{{ __('Planes existentes') }}</h3>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('accounts.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                                        {{ __('Gestionar cuentas') }}
                                    </a>
                                    <button @click="showForm = !showForm; editingPlan = null; resetForm()" class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-600 focus:bg-brand-600 active:bg-brand focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <span x-text="showForm ? 'Cancelar' : 'Nuevo Plan'"></span>
                                    </button>
                                </div>
                            </div>

                            @if ($adminPlans->isEmpty())
                                <p class="text-gray-500 text-center py-4">{{ __('No hay planes creados.') }}</p>
                            @else
                                <div class="space-y-4">
                                    @foreach ($adminPlans as $plan)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-medium text-gray-900">{{ $plan->name }}</p>
                                                        @if (!$plan->is_active)
                                                            <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">{{ __('Inactivo') }}</span>
                                                        @endif
                                                    </div>
                                                    @if ($plan->description)
                                                        <p class="text-sm text-gray-600 mt-1">{{ $plan->description }}</p>
                                                    @endif

                                                    @if ($plan->periods->count())
                                                        <div class="flex flex-wrap gap-2 mt-2">
                                                            @foreach ($plan->periods as $period)
                                                                 <span class="px-2 py-1 text-xs bg-indigo-50 text-indigo-700 rounded">
                                                                     {{ $period->recurrenceLabel() }} · ${{ number_format($period->price, 2) }} USD
                                                                 </span>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if ($plan->features->count())
                                                        <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                                                            @foreach ($plan->features as $feature)
                                                                <li>{{ $feature->description }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 shrink-0">
                                                    <button @click="editPlan({{ $plan->toJson() }})" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                                    <form method="POST" action="{{ route('plans.destroy', $plan) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Formulario crear/editar --}}
                        <div x-show="showForm" x-transition class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
                            <h3 class="font-semibold text-lg text-gray-900 mb-4" x-text="editingPlan ? 'Editar Plan' : 'Nuevo Plan'"></h3>

                            <form :action="editingPlan ? '{{ url('configuracion/plans') }}/' + editingPlan.id : '{{ route('plans.store') }}'" method="POST">
                                @csrf
                                <template x-if="editingPlan">
                                    <input type="hidden" name="_method" value="PATCH">
                                </template>

                                {{-- Nombre --}}
                                <div>
                                    <x-input-label for="name" :value="__('Nombre')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" x-model="form.name" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                {{-- Descripción --}}
                                <div class="mt-4">
                                    <x-input-label for="description" :value="__('Descripción')" />
                                    <textarea id="description" name="description" rows="2" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" x-model="form.description"></textarea>
                                </div>

                                {{-- Activo + Orden + Límites --}}
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" :checked="form.is_active" @change="form.is_active = $event.target.checked">
                                            <span class="ms-2 text-sm text-gray-600">{{ __('Activo') }}</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="is_free" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" :checked="form.is_free" @change="form.is_free = $event.target.checked">
                                            <span class="ms-2 text-sm text-gray-600">{{ __('Plan gratuito') }}</span>
                                        </label>
                                    </div>
                                    <div>
                                        <x-input-label for="max_artworks" :value="__('Máximo de obras')" />
                                        <x-text-input id="max_artworks" class="block mt-1 w-full" type="number" min="0" name="max_artworks" x-model="form.max_artworks" />
                                        <p class="mt-1 text-xs text-gray-400">{{ __('Déjalo vacío para obras ilimitadas.') }}</p>
                                    </div>
                                    <div>
                                        <x-input-label for="sort_order" :value="__('Orden')" />
                                        <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" x-model="form.sort_order" />
                                    </div>
                                </div>

                                {{-- Períodos --}}
                                <div class="mt-6 border-t pt-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">{{ __('Períodos de suscripción') }}</h4>
                                        <button type="button" @click="addPeriod()" class="text-sm text-indigo-600 hover:text-indigo-900">+ {{ __('Agregar') }}</button>
                                    </div>
                                    <template x-for="(period, index) in form.periods" :key="index">
                                        <div class="grid grid-cols-4 gap-3 mb-3 items-end">
                                            <div>
                                                <label class="text-xs text-gray-500">{{ __('Número') }}</label>
                                                <input type="number" :name="'periods[' + index + '][number]'" x-model="period.number" min="1" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500">{{ __('Período') }}</label>
                                                <select :name="'periods[' + index + '][period]'" x-model="period.period" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                                    <option value="monthly">{{ __('Mensual') }}</option>
                                                    <option value="quarterly">{{ __('Trimestral') }}</option>
                                                    <option value="semiannual">{{ __('Semestral') }}</option>
                                                    <option value="annual">{{ __('Anual') }}</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500">{{ __('Precio (USD)') }}</label>
                                                <input type="number" :name="'periods[' + index + '][price]'" x-model="period.price" min="0" step="0.01" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                            </div>
                                            <div class="flex justify-center">
                                                <button type="button" @click="form.periods.splice(index, 1)" class="text-red-500 hover:text-red-700 text-sm">✕</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Características --}}
                                <div class="mt-6 border-t pt-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">{{ __('Características') }}</h4>
                                        <button type="button" @click="addFeature()" class="text-sm text-indigo-600 hover:text-indigo-900">+ {{ __('Agregar') }}</button>
                                    </div>
                                    <template x-for="(feature, index) in form.features" :key="index">
                                        <div class="flex gap-3 mb-3 items-end">
                                            <div class="flex-1">
                                                <input type="text" :name="'features[' + index + '][description]'" x-model="feature.description" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="{{ __('Descripción de la característica') }}" required>
                                            </div>
                                            <button type="button" @click="form.features.splice(index, 1)" class="text-red-500 hover:text-red-700 text-sm">✕</button>
                                        </div>
                                    </template>
                                </div>

                                {{-- Términos legales --}}
                                <div class="mt-6 border-t pt-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">{{ __('Términos legales') }}</h4>
                                        <button type="button" @click="addLegalTerm()" class="text-sm text-indigo-600 hover:text-indigo-900">+ {{ __('Agregar') }}</button>
                                    </div>
                                    <template x-for="(term, index) in form.legal_terms" :key="index">
                                        <div class="grid grid-cols-3 gap-3 mb-3 items-end">
                                            <div class="col-span-2">
                                                <input type="text" :name="'legal_terms[' + index + '][description]'" x-model="term.description" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="{{ __('Descripción del término') }}" required>
                                            </div>
                                            <div class="flex gap-2 items-end">
                                                <input type="url" :name="'legal_terms[' + index + '][link]'" x-model="term.link" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="https://...">
                                                <button type="button" @click="form.legal_terms.splice(index, 1)" class="text-red-500 hover:text-red-700 text-sm">✕</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="mt-6 flex items-center justify-end">
                                    <button type="button" @click="showForm = false; editingPlan = null; resetForm()" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Cancelar') }}
                                    </button>
                                    <x-primary-button class="ms-4">
                                        <span x-text="editingPlan ? '{{ __('Guardar') }}' : '{{ __('Crear') }}'"></span>
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tab: Paquetes de tokens (admin) --}}
            @if ($user->isAdmin())
                <div x-show="tab === 'packages'" x-transition>
                    <div x-data="packageForm()">
                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900">{{ __('Paquetes de tokens') }}</h3>
                                <div class="flex items-center gap-2">
                                    <button @click="showForm = true; editingPackage = null; resetForm(); window.scrollTo({ top: 0, behavior: 'smooth' });"
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg">
                                        {{ __('Nuevo paquete') }}
                                    </button>
                                </div>
                            </div>

                            @if ($adminPackages->isEmpty())
                                <p class="text-gray-500 text-center py-4">{{ __('No hay paquetes creados.') }}</p>
                            @else
                                <div class="space-y-4">
                                    @foreach ($adminPackages as $package)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-medium text-gray-900">{{ $package->name }}</p>
                                                        @if (!$package->is_active)
                                                            <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">{{ __('Inactivo') }}</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        {{ $package->tokens }} tokens · ${{ number_format($package->price_usd, 2) }} USD
                                                    </p>
                                                    @if ($package->description)
                                                        <p class="text-sm text-gray-600 mt-0.5">{{ $package->description }}</p>
                                                    @endif
                                                    @if (!$package->paddle_price_id)
                                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-amber-50 text-amber-700 rounded">{{ __('Sin sincronizar con Paddle') }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 shrink-0">
                                                    <button @click="editPackage({{ $package->toJson() }})" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                                    <form method="POST" action="{{ route('packages.destroy', $package) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Formulario crear/editar --}}
                        <div x-show="showForm" x-transition class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
                            <h3 class="font-semibold text-lg text-gray-900 mb-4" x-text="editingPackage ? '{{ __('Editar paquete') }}' : '{{ __('Nuevo paquete') }}'"></h3>

                            <form :action="editingPackage ? '{{ url('configuracion/packages') }}/' + editingPackage.id : '{{ route('packages.store') }}'" method="POST">
                                @csrf
                                <template x-if="editingPackage">
                                    <input type="hidden" name="_method" value="PATCH">
                                </template>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="pkg_name" :value="__('Nombre')" />
                                        <x-text-input id="pkg_name" class="block mt-1 w-full" type="text" name="name" x-model="form.name" required />
                                    </div>
                                    <div>
                                        <x-input-label for="pkg_tokens" :value="__('Número de tokens')" />
                                        <x-text-input id="pkg_tokens" class="block mt-1 w-full" type="number" min="1" name="tokens" x-model="form.tokens" required />
                                    </div>
                                    <div>
                                        <x-input-label for="pkg_price" :value="__('Precio (USD)')" />
                                        <x-text-input id="pkg_price" class="block mt-1 w-full" type="number" step="0.01" min="0.5" name="price_usd" x-model="form.price_usd" required />
                                    </div>
                                    <div>
                                        <x-input-label for="pkg_sort" :value="__('Orden')" />
                                        <x-text-input id="pkg_sort" class="block mt-1 w-full" type="number" min="0" name="sort_order" x-model="form.sort_order" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label for="pkg_desc" :value="__('Descripción')" />
                                        <x-text-input id="pkg_desc" class="block mt-1 w-full" type="text" name="description" x-model="form.description" />
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                            <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            {{ __('Activo') }}
                                        </label>
                                        <input type="hidden" name="is_active" value="0" :disabled="true">
                                    </div>
                                </div>

                                <div class="mt-6 flex items-center justify-end">
                                    <button type="button" @click="showForm = false; editingPackage = null; resetForm()" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Cancelar') }}
                                    </button>
                                    <x-primary-button class="ms-4">
                                        <span x-text="editingPackage ? '{{ __('Guardar') }}' : '{{ __('Crear') }}'"></span>
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tab: Usos de tokens (admin) --}}
            @if ($user->isAdmin())
                <div x-show="tab === 'token-functions'" x-transition
                     x-data="tokenUsages(@js($adminTokenActions), @js($adminTokenFunctions), @js(request('sub', 'functions')))">

                    {{-- Sub-pestañas: Funciones | Acciones --}}
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex gap-6">
                            <button @click="sub = 'functions'" :class="sub === 'functions' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                                {{ __('Funciones de tokens') }}
                            </button>
                            <button @click="sub = 'actions'" :class="sub === 'actions' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                                {{ __('Acciones permitidas') }}
                            </button>
                        </nav>
                    </div>

                    {{-- Panel: Funciones --}}
                    <div x-show="sub === 'functions'">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm text-gray-600">{{ __('Las funciones agrupan acciones y consumen tokens.') }}</p>
                            <button @click="openFunctionModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg">
                                {{ __('Nueva función') }}
                            </button>
                        </div>

                        @if ($adminTokenFunctions->isEmpty())
                            <p class="text-gray-500 text-center py-8 bg-white shadow-sm sm:rounded-lg">{{ __('No hay funciones creadas.') }}</p>
                        @else
                            <div class="bg-white shadow-sm sm:rounded-lg divide-y divide-gray-100">
                                @foreach ($adminTokenFunctions as $tf)
                                    <div class="p-5 flex items-start justify-between gap-4">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <p class="font-medium text-gray-900">{{ $tf->name }}</p>
                                                @if (!$tf->is_active)
                                                    <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">{{ __('Inactivo') }}</span>
                                                @endif
                                            </div>
                                            @if ($tf->description)
                                                <p class="text-sm text-gray-600 mt-0.5">{{ $tf->description }}</p>
                                            @endif
                                            @if ($tf->actions->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-1.5">
                                                    @foreach ($tf->actions as $action)
                                                        <span class="px-2 py-0.5 text-xs bg-indigo-50 text-indigo-700 rounded-full">{{ $action->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <span class="text-sm font-semibold text-gray-700">
                                                <span class="text-lg font-bold">{{ $tf->tokens }}</span>
                                                {{ __('tokens') }}
                                            </span>
                                            <button @click="openFunctionModal({{ $tf->toJson() }})" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                            <form method="POST" action="{{ route('token-functions.destroy', $tf) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Panel: Acciones --}}
                    <div x-show="sub === 'actions'">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm text-gray-600">{{ __('Catálogo de acciones que puede incluir una función.') }}</p>
                            <button @click="openActionModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg">
                                {{ __('Nueva acción') }}
                            </button>
                        </div>

                        @if ($adminTokenActions->isEmpty())
                            <p class="text-gray-500 text-center py-8 bg-white shadow-sm sm:rounded-lg">{{ __('No hay acciones creadas.') }}</p>
                        @else
                            <div class="bg-white shadow-sm sm:rounded-lg divide-y divide-gray-100">
                                @foreach ($adminTokenActions as $action)
                                    <div class="p-5 flex items-start justify-between gap-4">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <p class="font-medium text-gray-900">{{ $action->name }}</p>
                                                @if (!$action->is_active)
                                                    <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">{{ __('Inactivo') }}</span>
                                                @endif
                                            </div>
                                            @if ($action->description)
                                                <p class="text-sm text-gray-600 mt-0.5">{{ $action->description }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <button @click="openActionModal({{ $action->toJson() }})" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                            <form method="POST" action="{{ route('token-actions.destroy', $action) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Modal: Función --}}
                    <div x-show="functionModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
                        <div class="flex items-center justify-center min-h-screen px-4">
                            <div class="fixed inset-0 bg-brand-800/60" @click="functionModalOpen = false"></div>
                            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl p-6 my-8">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold text-lg text-gray-900" x-text="editingFunction ? '{{ __('Editar función') }}' : '{{ __('Nueva función') }}'"></h3>
                                    <button @click="functionModalOpen = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                                </div>

                                <form :action="editingFunction ? '{{ url('configuracion/token-functions') }}/' + editingFunction.id : '{{ route('token-functions.store') }}'" method="POST">
                                    @csrf
                                    <template x-if="editingFunction">
                                        <input type="hidden" name="_method" value="PATCH">
                                    </template>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="tf_name" :value="__('Nombre')" />
                                            <x-text-input id="tf_name" class="block mt-1 w-full" type="text" name="name" x-model="form.name" required />
                                        </div>
                                        <div>
                                            <x-input-label for="tf_tokens" :value="__('Valor en tokens')" />
                                            <x-text-input id="tf_tokens" class="block mt-1 w-full" type="number" min="1" name="tokens" x-model="form.tokens" required />
                                        </div>
                                        <div>
                                            <x-input-label for="tf_sort" :value="__('Orden')" />
                                            <x-text-input id="tf_sort" class="block mt-1 w-full" type="number" min="0" name="sort_order" x-model="form.sort_order" />
                                        </div>
                                        <div>
                                            <label class="inline-flex items-center gap-2 text-sm text-gray-700 mt-6">
                                                <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                {{ __('Activo') }}
                                            </label>
                                            <input type="hidden" name="is_active" value="0" :disabled="true">
                                        </div>
                                        <div class="md:col-span-2">
                                            <x-input-label for="tf_desc" :value="__('Descripción')" />
                                            <x-text-input id="tf_desc" class="block mt-1 w-full" type="text" name="description" x-model="form.description" />
                                        </div>
                                        <div class="md:col-span-2">
                                            <x-input-label :value="__('Acciones incluidas')" />
                                            <p class="mt-1 text-xs text-gray-500">{{ __('Selecciona una o más acciones.') }}</p>
                                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                <template x-for="action in actions" :key="action.id">
                                                    <label class="flex items-start gap-2 text-sm text-gray-700 border border-gray-200 rounded-lg px-3 py-2 cursor-pointer hover:border-indigo-300">
                                                        <input type="checkbox" name="action_ids[]" :value="action.id" x-model="form.action_ids"
                                                            :disabled="!action.is_active"
                                                            class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                        <span class="min-w-0">
                                                            <span class="block font-medium" x-text="action.name"></span>
                                                            <span class="block text-xs text-gray-500" x-text="action.description || ''"></span>
                                                        </span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex items-center justify-end gap-3">
                                        <button type="button" @click="functionModalOpen = false" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('Cancelar') }}
                                        </button>
                                        <x-primary-button>
                                            <span x-text="editingFunction ? '{{ __('Guardar') }}' : '{{ __('Crear') }}'"></span>
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal: Acción --}}
                    <div x-show="actionModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
                        <div class="flex items-center justify-center min-h-screen px-4">
                            <div class="fixed inset-0 bg-brand-800/60" @click="actionModalOpen = false"></div>
                            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg p-6 my-8">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold text-lg text-gray-900" x-text="editingAction ? '{{ __('Editar acción') }}' : '{{ __('Nueva acción') }}'"></h3>
                                    <button @click="actionModalOpen = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                                </div>

                                <form :action="editingAction ? '{{ url('configuracion/token-actions') }}/' + editingAction.id : '{{ route('token-actions.store') }}'" method="POST">
                                    @csrf
                                    <template x-if="editingAction">
                                        <input type="hidden" name="_method" value="PATCH">
                                    </template>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="ta_name" :value="__('Nombre')" />
                                            <x-text-input id="ta_name" class="block mt-1 w-full" type="text" name="name" x-model="actionForm.name" required />
                                        </div>
                                        <div>
                                            <x-input-label for="ta_sort" :value="__('Orden')" />
                                            <x-text-input id="ta_sort" class="block mt-1 w-full" type="number" min="0" name="sort_order" x-model="actionForm.sort_order" />
                                        </div>
                                        <div class="md:col-span-2">
                                            <x-input-label for="ta_desc" :value="__('Descripción')" />
                                            <x-text-input id="ta_desc" class="block mt-1 w-full" type="text" name="description" x-model="actionForm.description" />
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                <input type="checkbox" name="is_active" value="1" x-model="actionForm.is_active" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                {{ __('Activo') }}
                                            </label>
                                            <input type="hidden" name="is_active" value="0" :disabled="true">
                                        </div>
                                    </div>

                                    <div class="mt-6 flex items-center justify-end gap-3">
                                        <button type="button" @click="actionModalOpen = false" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('Cancelar') }}
                                        </button>
                                        <x-primary-button>
                                            <span x-text="editingAction ? '{{ __('Guardar') }}' : '{{ __('Crear') }}'"></span>
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tab: IA (admin) --}}
            @if ($user->isAdmin())
                <div x-show="tab === 'ia'" x-transition>
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-lg text-gray-900">{{ __('IA (modelos de Groq)') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Modelos que usará el asistente de soporte y demás agentes.') }}</p>

                        <form method="POST" action="{{ route('ai.update') }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="chat_model" :value="__('Modelo de chat')" />
                                <x-text-input id="chat_model" name="chat_model" type="text" class="mt-1 block w-full" :value="old('chat_model', $aiSettings['chat_model'] ?? '')" required />
                                <p class="mt-1 text-xs text-gray-400">{{ __('Respuestas del soporte (ej. qwen/qwen3.8-27b).') }}</p>
                                <x-input-error :messages="$errors->get('chat_model')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="router_model" :value="__('Modelo de ruteo')" />
                                <x-text-input id="router_model" name="router_model" type="text" class="mt-1 block w-full" :value="old('router_model', $aiSettings['router_model'] ?? '')" required />
                                <p class="mt-1 text-xs text-gray-400">{{ __('Clasificación del tema de cada mensaje (barato y rápido).') }}</p>
                                <x-input-error :messages="$errors->get('router_model')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="backup_model" :value="__('Modelo de respaldo (opcional)')" />
                                <x-text-input id="backup_model" name="backup_model" type="text" class="mt-1 block w-full" :value="old('backup_model', $aiSettings['backup_model'] ?? '')" />
                                <p class="mt-1 text-xs text-gray-400">{{ __('Si el principal falla, se intenta con este.') }}</p>
                                <x-input-error :messages="$errors->get('backup_model')" class="mt-2" />
                            </div>
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                                @if (session('status') === 'ai-updated')
                                    <p class="text-sm text-gray-600">{{ __('Guardado.') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function tokenUsages(actionData, functionData, initialSub) {
            return {
                sub: (initialSub || 'functions'),
                actions: (actionData || []),
                functions: (functionData || []),
                /* Modal función */
                functionModalOpen: false,
                editingFunction: null,
                form: {
                    name: '',
                    description: '',
                    tokens: 1,
                    sort_order: 0,
                    is_active: true,
                    action_ids: [],
                },
                resetFunctionForm() {
                    this.form = {
                        name: '',
                        description: '',
                        tokens: 1,
                        sort_order: 0,
                        is_active: true,
                        action_ids: [],
                    };
                },
                openFunctionModal(tf) {
                    this.editingFunction = tf || null;
                    this.resetFunctionForm();
                    if (tf) {
                        this.form = {
                            name: tf.name,
                            description: tf.description || '',
                            tokens: tf.tokens,
                            sort_order: tf.sort_order,
                            is_active: tf.is_active,
                            action_ids: (tf.actions || []).map(a => a.id),
                        };
                    }
                    this.functionModalOpen = true;
                },
                closeFunctionModal() {
                    this.functionModalOpen = false;
                    this.editingFunction = null;
                },
                /* Modal acción */
                actionModalOpen: false,
                editingAction: null,
                actionForm: {
                    name: '',
                    description: '',
                    sort_order: 0,
                    is_active: true,
                },
                resetActionForm() {
                    this.actionForm = {
                        name: '',
                        description: '',
                        sort_order: 0,
                        is_active: true,
                    };
                },
                openActionModal(action) {
                    this.editingAction = action || null;
                    this.resetActionForm();
                    if (action) {
                        this.actionForm = {
                            name: action.name,
                            description: action.description || '',
                            sort_order: action.sort_order,
                            is_active: action.is_active,
                        };
                    }
                    this.actionModalOpen = true;
                },
                closeActionModal() {
                    this.actionModalOpen = false;
                    this.editingAction = null;
                },
            };
        }
        function packageForm() {
            return {
                showForm: false,
                editingPackage: null,
                form: {
                    name: '',
                    description: '',
                    tokens: '',
                    price_usd: '',
                    sort_order: 0,
                    is_active: true,
                },
                resetForm() {
                    this.form = {
                        name: '',
                        description: '',
                        tokens: '',
                        price_usd: '',
                        sort_order: 0,
                        is_active: true,
                    };
                },
                editPackage(pkg) {
                    this.editingPackage = pkg;
                    this.form = {
                        name: pkg.name,
                        description: pkg.description || '',
                        tokens: pkg.tokens,
                        price_usd: pkg.price_usd,
                        sort_order: pkg.sort_order,
                        is_active: pkg.is_active,
                    };
                    this.showForm = true;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
            };
        }
        function planForm() {
            return {
                showForm: false,
                editingPlan: null,
                form: {
                    name: '',
                    description: '',
                    is_active: true,
                    is_free: false,
                    max_artworks: '',
                    sort_order: 0,
                    periods: [],
                    features: [],
                    legal_terms: [],
                },
                resetForm() {
                    this.form = {
                        name: '',
                        description: '',
                        is_active: true,
                        is_free: false,
                        max_artworks: '',
                        sort_order: 0,
                        periods: [],
                        features: [],
                        legal_terms: [],
                    };
                },
                editPlan(plan) {
                    this.editingPlan = plan;
                    this.form = {
                        name: plan.name,
                        description: plan.description || '',
                        is_active: plan.is_active,
                        is_free: plan.is_free,
                        max_artworks: plan.max_artworks ?? '',
                        sort_order: plan.sort_order,
                        periods: plan.periods.map(p => ({...p})),
                        features: plan.features.map(f => ({...f})),
                        legal_terms: plan.legal_terms.map(t => ({...t})),
                    };
                    this.showForm = true;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                addPeriod() {
                    this.form.periods.push({ number: 1, period: 'monthly', price: 0 });
                },
                addFeature() {
                    this.form.features.push({ description: '' });
                },
                addLegalTerm() {
                    this.form.legal_terms.push({ description: '', link: '' });
                },
            };
        }
    </script>
</x-app-layout>
