<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: '{{ $user->isAdmin() ? request('tab', 'seguridad') : 'seguridad' }}' }">
            {{-- Tabs --}}
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex gap-6">
                    <button @click="tab = 'seguridad'" :class="tab === 'seguridad' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        {{ __('Seguridad') }}
                    </button>
                    @if ($user->isAdmin())
                        <button @click="tab = 'planes'" :class="tab === 'planes' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            {{ __('Planes') }}
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
                                        {{ __('Gestioná tu email y contraseña.') }}
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

            {{-- Tab: Planes (admin) --}}
            @if ($user->isAdmin())
                <div x-show="tab === 'planes'" x-transition>
                    <div x-data="planForm()">
                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900">{{ __('Planes existentes') }}</h3>
                                <button @click="showForm = !showForm; editingPlan = null; resetForm()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <span x-text="showForm ? 'Cancelar' : 'Nuevo Plan'"></span>
                                </button>
                            </div>

                            @if ($plans->isEmpty())
                                <p class="text-gray-500 text-center py-4">{{ __('No hay planes creados.') }}</p>
                            @else
                                <div class="space-y-4">
                                    @foreach ($plans as $plan)
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
                                                                    {{ $period->number }} {{ $period->period_label }} · ${{ number_format($period->price, 2) }} USD
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

                                {{-- Activo + Orden --}}
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" :checked="form.is_active" @change="form.is_active = $event.target.checked">
                                            <span class="ms-2 text-sm text-gray-600">{{ __('Activo') }}</span>
                                        </label>
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
        </div>
    </div>

    <script>
        function planForm() {
            return {
                showForm: false,
                editingPlan: null,
                form: {
                    name: '',
                    description: '',
                    is_active: true,
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
