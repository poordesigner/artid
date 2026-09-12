<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-end gap-3 mb-4">
                        <a href="{{ route('series.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Series') }}
                        </a>
                        @if ($artist->currentMaxArtworks() !== null && $artist->activeArtworksCount() >= $artist->currentMaxArtworks())
                            <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 rounded-md text-xs uppercase tracking-widest cursor-not-allowed">{{ __('Límite alcanzado') }}</span>
                        @else
                            <a href="{{ route('artworks.create') }}" class="inline-flex items-center px-4 py-2 bg-brand border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-600 focus:bg-brand-600 active:bg-brand focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('New Artwork') }}
                            </a>
                        @endif
                    </div>

                    @php
                        $max = $artist->currentMaxArtworks();
                        $count = $artist->activeArtworksCount();
                    @endphp
                    @if ($max !== null)
                        <p class="mb-4 text-sm text-gray-600">
                            {{ __('Obras registradas') }}: <strong>{{ $count }} / {{ $max }}</strong>
                        </p>
                    @endif

                    {{-- Filtros y orden --}}
                    <div class="mb-4 flex flex-wrap items-center gap-2">
                        <a href="{{ route('artworks.index', ['status' => 'all', 'sort' => $sort]) }}"
                           class="px-3 py-1.5 rounded-md text-xs font-medium uppercase tracking-wider transition
                                  {{ $status === 'all' ? 'bg-brand text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ __('Todas') }}
                        </a>
                        <a href="{{ route('artworks.index', ['status' => 'active', 'sort' => $sort]) }}"
                           class="px-3 py-1.5 rounded-md text-xs font-medium uppercase tracking-wider transition
                                  {{ $status === 'active' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ __('Activas') }}
                        </a>
                        <a href="{{ route('artworks.index', ['status' => 'inactive', 'sort' => $sort]) }}"
                           class="px-3 py-1.5 rounded-md text-xs font-medium uppercase tracking-wider transition
                                  {{ $status === 'inactive' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ __('Inactivas') }}
                        </a>
                        <span class="mx-1 text-gray-300">|</span>
                        <select onchange="window.location.href = this.value" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-xs font-medium text-gray-700">
                            <option value="{{ route('artworks.index', ['status' => $status, 'sort' => 'recent']) }}" @selected($sort === 'recent')>{{ __('Más recientes') }}</option>
                            <option value="{{ route('artworks.index', ['status' => $status, 'sort' => 'oldest']) }}" @selected($sort === 'oldest')>{{ __('Más antiguas') }}</option>
                            <option value="{{ route('artworks.index', ['status' => $status, 'sort' => 'title']) }}" @selected($sort === 'title')>{{ __('Título A-Z') }}</option>
                        </select>
                        @if ($status === 'inactive' || $status === 'all')
                            <span class="ml-auto text-xs text-gray-400">{{ __('Las obras inactivas se muestran atenuadas.') }}</span>
                        @endif
                    </div>

                    @if ($artworks->isEmpty())
                        <p class="text-gray-500 text-center py-8">
                            @if ($status === 'inactive')
                                {{ __('No hay obras inactivas.') }}
                            @elseif ($status === 'active')
                                {{ __('No hay obras activas.') }}
                            @else
                                {{ __('No artworks yet.') }}
                            @endif
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full w-full table-fixed divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="w-52 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('QR') }}</th>
                                        <th class="w-64 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider max-w-[16rem]">{{ __('Title') }}</th>
                                        <th class="w-16 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Año') }}</th>
                                        <th class="w-28 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Registrada') }}</th>
                                        <th class="w-28 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th class="w-56 px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($artworks as $artwork)
                                        @php $inactive = $artwork->status === 'archived'; $isValidated = $artwork->isQrValidated(); @endphp
                                        <tr class="{{ $inactive ? 'bg-gray-50 opacity-60' : '' }}">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    @if($isValidated)
                                                        <a href="{{ route('artworks.qr', $artwork) }}" target="_blank" title="{{ $artwork->artwork_id }}">
                                                            <img src="{{ route('artworks.qr', $artwork) }}" alt="QR {{ $artwork->artwork_id }}" class="h-24 w-24 object-contain" style="width:6rem; height:6rem;" />
                                                        </a>
                                                    @else
                                                        <span class="inline-flex items-center justify-center h-24 w-24 rounded border border-dashed border-gray-300 bg-amber-50 text-[9px] text-amber-700 text-center" style="width:6rem; height:6rem;">QR<br>pendiente</span>
                                                    @endif
                                                    @if($artwork->imageUrl())
                                                        <a href="{{ route('artworks.show', $artwork) }}" title="{{ $artwork->title }}" class="h-24 w-24 flex items-center justify-center rounded border border-gray-200 bg-white overflow-hidden" style="width:6rem; height:6rem;">
                                                            <img src="{{ $artwork->imageUrl() }}" alt="{{ $artwork->title }}" class="max-h-full max-w-full object-contain" />
                                                        </a>
                                                    @else
                                                        <span class="inline-flex items-center justify-center h-24 w-24 rounded border border-dashed border-gray-200 bg-gray-50 text-[8px] text-gray-400" style="width:6rem; height:6rem;">sin img</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 max-w-[16rem]">
                                                <a href="{{ route('artworks.show', $artwork) }}" class="hover:text-indigo-600 font-medium block truncate" title="{{ $artwork->title }}">{{ $artwork->title }}</a>
                                                <span class="block font-mono text-xs text-gray-400 truncate">{{ $artwork->artwork_id }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $artwork->year ?? '—' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $artwork->created_at->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                @if ($inactive)
                                                    <span class="px-2 py-0.5 bg-gray-200 text-gray-600 rounded-full text-xs font-medium">{{ __('Inactiva') }}</span>
                                                @elseif (!$isValidated)
                                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">{{ __('Pendiente QR') }}</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">{{ __('Activa') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                                                @if(!$isValidated)
                                                    <span x-data="{ open:false, tip:false, c1:false, c2:false, c3:false, c4:false, c5:false, c6:false }">
                                                        <button @click="open=true" class="ms-2 px-2 py-1 bg-amber-500 text-white rounded text-xs hover:bg-amber-600">Generar QR</button>
                                                        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
                                                            <div class="flex items-center justify-center min-h-screen px-4">
                                                                <div class="fixed inset-0 bg-black/50" @click="open=false"></div>
                                                                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg p-6 text-left">
                                                                    <h3 class="font-semibold text-gray-900">Validar y Generar QR</h3>
                                                                    <p class="mt-1 text-sm text-gray-600 break-words" style="text-wrap: auto; overflow-wrap: anywhere; word-break: break-word;">Favor revisar detalladamente que cada campo contenga la información definitiva que va a quedar en la Ficha de la Obra. Al momento de generar el código QR, estos campos quedan de solo lectura y no se podrán volver a modificar.</p>
                                                                    <div class="mt-3 relative inline-block" @mouseenter="tip=true" @mouseleave="tip=false">
                                                                        <button type="button" @click="tip=!tip" class="text-[10px] uppercase tracking-wider font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 px-2.5 py-1 rounded border border-gray-200">CONOCER MÁS...</button>
                                                                        <div x-show="tip" x-cloak class="absolute z-20 left-0 mt-2 w-80 p-3 bg-white border border-gray-200 shadow-lg rounded text-xs text-gray-600 break-words" style="text-wrap: auto; overflow-wrap: anywhere; word-break: break-word;">Para garantizar que el código QR pertenece a una obra, se utilizan llaves criptográficas únicas, que se generan usando parte de los datos que definen cada obra como tal. Por esta razón, una vez creado el código QR, éstos datos quedan bloqueados y no se pueden modificar.</div>
                                                                    </div>
                                                                    <div class="mt-4 space-y-3 text-sm">
                                                                        <label class="flex gap-2 items-start p-2 border rounded hover:bg-gray-50 w-full"><input type="checkbox" x-model="c1" class="mt-1 rounded border-gray-300 text-indigo-600"><span class="flex-1 min-w-0 w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block;"><span class="text-xs uppercase text-gray-400">Título</span><br>@if($artwork->title)<span class="font-medium block w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block; width:100%;">{{ $artwork->title }}</span>@else<span class="text-amber-600">sin diligenciar</span> <a href="{{ route('artworks.edit', $artwork) }}" class="underline text-indigo-600">editar obra</a>@endif</span></label>
                                                                        <label class="flex gap-2 items-start p-2 border rounded hover:bg-gray-50 w-full"><input type="checkbox" x-model="c2" class="mt-1 rounded border-gray-300 text-indigo-600"><span class="flex-1 min-w-0 w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block;"><span class="text-xs uppercase text-gray-400">Imagen</span><br>@if($artwork->imageUrl())<img src="{{ $artwork->imageUrl() }}" class="h-20 object-contain border rounded bg-white">@else<span class="text-amber-600">sin diligenciar</span> <a href="{{ route('artworks.edit', $artwork) }}" class="underline text-indigo-600">editar obra</a>@endif</span></label>
                                                                        <label class="flex gap-2 items-start p-2 border rounded hover:bg-gray-50 w-full"><input type="checkbox" x-model="c3" class="mt-1 rounded border-gray-300 text-indigo-600"><span class="flex-1 min-w-0 w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block;"><span class="text-xs uppercase text-gray-400">Año</span><br>@if($artwork->year)<span class="font-medium block w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block; width:100%;">{{ $artwork->year }}</span>@else<span class="text-amber-600">sin diligenciar</span> <a href="{{ route('artworks.edit', $artwork) }}" class="underline text-indigo-600">editar obra</a>@endif</span></label>
                                                                         <label class="flex gap-2 items-start p-2 border rounded hover:bg-gray-50 w-full"><input type="checkbox" x-model="c4" class="mt-1 rounded border-gray-300 text-indigo-600"><span class="flex-1 min-w-0 w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block;"><span class="text-xs uppercase text-gray-400">Edición</span><br>@if($artwork->edition)<span class="font-medium block w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block; width:100%;">{{ $artwork->edition }}</span>@else<span class="font-medium block w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block; width:100%;">Pieza Única</span>@endif</span></label>
                                                                         <label class="flex gap-2 items-start p-2 border rounded hover:bg-gray-50 w-full"><input type="checkbox" x-model="c5" class="mt-1 rounded border-gray-300 text-indigo-600"><span class="flex-1 min-w-0 w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block;"><span class="text-xs uppercase text-gray-400">Nº Copias</span><br>@php $isTiraje5 = $artwork->edition && str_contains($artwork->edition, '/'); @endphp @if($isTiraje5) @if($artwork->edition_number)<span class="font-medium block w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block; width:100%;">{{ $artwork->edition_number }}</span>@else<span class="text-amber-600">sin diligenciar</span> <a href="{{ route('artworks.edit', $artwork) }}" class="underline text-indigo-600">editar obra</a>@endif @else <span class="font-medium block w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block; width:100%;">1</span> @endif</span></label>
                                                                        <label class="flex gap-2 items-start p-2 border rounded hover:bg-gray-50 w-full"><input type="checkbox" x-model="c6" class="mt-1 rounded border-gray-300 text-indigo-600"><span class="flex-1 min-w-0 w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block;"><span class="text-xs uppercase text-gray-400">Dimensiones</span><br>@if($artwork->dimensions)<span class="font-medium block w-full" style="overflow-wrap: anywhere; word-break: break-word; display:block; width:100%;">{{ $artwork->dimensions }}</span>@else<span class="text-amber-600">sin diligenciar</span> <a href="{{ route('artworks.edit', $artwork) }}" class="underline text-indigo-600">editar obra</a>@endif</span></label>
                                                                    </div>
                                                                    <p class="mt-4 text-xs text-gray-600 bg-amber-50 border border-amber-200 p-3 rounded">Acepto generar el Código QR para esta obra.</p>
                                                                    <form method="POST" action="{{ route('artworks.validate-qr', $artwork) }}" class="mt-4">
                                                                        @csrf
                                                                        <input type="hidden" name="accept_qr" value="1">
                                                                        <div class="flex justify-end gap-2">
                                                                            <button type="button" @click="open=false" class="underline text-sm text-gray-600">Cancelar</button>
                                                                            <button type="submit" :disabled="!(c1&&c2&&c3&&c4&&c5&&c6)" :class="!(c1&&c2&&c3&&c4&&c5&&c6) ? 'opacity-50 cursor-not-allowed' : ''" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded hover:bg-emerald-700">Aceptar y generar QR</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>
                                                @else
                                                    @php $hasCoa = $artwork->certificates()->where('status','valid')->exists(); @endphp
                                                    @if(!$hasCoa)
                                                        <a href="{{ route('artworks.show', $artwork) }}#coa" title="Certificado de Autenticidad" class="ms-2 px-2 py-1 bg-brand text-white rounded text-xs hover:bg-brand-700">Crear COA</a>
                                                    @else
                                                        <span class="ms-2 text-emerald-600 text-xs">COA ✓</span>
                                                    @endif
                                                @endif
                                                <a href="{{ route('artworks.edit', $artwork) }}" class="ms-2 text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $artworks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
