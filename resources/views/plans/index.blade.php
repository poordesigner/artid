<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Planes de Suscripción') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif

            <div x-data="planForm()">
                {{-- Listado de planes --}}
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
                                            <p class="text-sm text-gray-500 mt-1">${{ number_format($plan->base_value, 2) }} USD / mes base</p>

                                            @if ($plan->periods->count())
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    @foreach ($plan->periods as $period)
                                                        <span class="px-2 py-1 text-xs bg-indigo-50 text-indigo-700 rounded">
                                                            {{ $period->number }} {{ $period->period_label }}
                                                            @if ($period->discount > 0)
                                                                · -{{ $period->discount }}%
                                                            @endif
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

                    <form :action="editingPlan ? '{{ url('planes') }}/' + editingPlan.id : '{{ route('plans.store') }}'" method="POST">
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

                        {{-- Valor base --}}
                        <div class="mt-4">
                            <x-input-label for="base_value" :value="__('Valor base (USD/mes)')" />
                            <x-text-input id="base_value" class="block mt-1 w-full" type="number" step="0.01" name="base_value" x-model="form.base_value" required />
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
                                        <label class="text-xs text-gray-500">{{ __('Descuento %') }}</label>
                                        <input type="number" :name="'periods[' + index + '][discount]'" x-model="period.discount" min="0" max="100" step="0.01" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
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
    </div>

    <script>
        function planForm() {
            return {
                showForm: false,
                editingPlan: null,
                form: {
                    name: '',
                    description: '',
                    base_value: '',
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
                        base_value: '',
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
                        base_value: plan.base_value,
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
                    this.form.periods.push({ number: 1, period: 'monthly', discount: 0 });
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
