<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :crumbs="[
            ['label' => __('Obras'), 'route' => route('artworks.index')],
        ]" :current="$artwork->title" />
        <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">
            {{ $artwork->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-md">{{ session('error') }}</div>
            @endif

            @if (session('secret_key'))
                <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-md">
                    <p class="text-sm font-semibold text-amber-800">{{ __('Llave secreta del nuevo propietario (guárdala, solo se muestra una vez):') }}</p>
                    <code class="mt-1 block text-xl font-mono text-amber-900">{{ session('secret_key') }}</code>
                </div>
            @endif

            @if (session('revealed'))
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <p class="text-sm font-semibold text-blue-800">{{ __('Propietario revelado:') }}</p>
                    <p class="mt-1 text-gray-900">{{ session('revealed')['name'] }}</p>
                    @if (session('revealed')['email'])
                        <p class="text-gray-700">{{ session('revealed')['email'] }}</p>
                    @endif
                    @if (session('revealed')['date'])
                        <p class="text-gray-600">{{ session('revealed')['date'] }}</p>
                    @endif
                </div>
            @endif

            <!-- Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col sm:flex-row gap-6">
                    @if ($artwork->image)
                        <div class="shrink-0">
                            <img src="{{ $artwork->imageUrl() }}" alt="{{ $artwork->title }}" class="h-48 object-contain rounded border border-gray-200" />
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-lg font-semibold text-gray-900">{{ $artwork->title }}</p>
                        <p class="font-mono text-sm text-gray-500">{{ $artwork->artwork_id }}</p>
                        <dl class="mt-3 text-sm text-gray-700 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @if ($artwork->year)
                                <div><dt class="font-medium text-gray-500">{{ __('Year') }}</dt><dd>{{ $artwork->year }}</dd></div>
                            @endif
                            @if ($artwork->edition)
                                <div><dt class="font-medium text-gray-500">{{ __('Edition') }}</dt><dd>{{ $artwork->edition }}</dd></div>
                            @endif
                            @if ($artwork->series)
                                <div><dt class="font-medium text-gray-500">{{ __('Series') }}</dt><dd>{{ $artwork->series }}</dd></div>
                            @endif
                            @if ($artwork->technique)
                                <div><dt class="font-medium text-gray-500">{{ __('Technique') }}</dt><dd>{{ $artwork->technique }}</dd></div>
                            @endif
                            @if ($artwork->dimensions)
                                <div><dt class="font-medium text-gray-500">{{ __('Dimensions') }}</dt><dd>{{ $artwork->dimensions }}</dd></div>
                            @endif
                        </dl>
                        @if ($artwork->description)
                            <p class="mt-3 text-sm text-gray-700">{{ $artwork->description }}</p>
                        @endif
                    </div>
                    <div class="shrink-0 text-center">
                        @if($artwork->isQrValidated())
                            <a href="{{ route('artworks.qr', $artwork) }}" target="_blank">
                                <img src="{{ route('artworks.qr', $artwork) }}" alt="QR" class="h-28 w-28 inline-block" />
                            </a>
                            <p class="mt-1 text-xs text-emerald-600">QR definitivo</p>
                        @else
                            <div class="h-28 w-28 flex items-center justify-center border border-dashed border-gray-300 rounded bg-gray-50 text-xs text-gray-400">QR pendiente</div>
                            <p class="mt-1 text-xs text-amber-600">Valida para generar</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Validación QR — estado -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($artwork->isQrValidated())
                        <div class="flex items-center gap-2 text-emerald-700">
                            <span class="inline-flex px-2 py-1 bg-emerald-50 border border-emerald-200 rounded text-xs font-semibold">QR validado</span>
                            <span class="text-xs text-gray-500">Bloqueado el {{ $artwork->qr_validated_at->format('d/m/Y H:i') }} — Hash: <span class="font-mono">{{ substr($artwork->qr_hash,0,12) }}…</span></span>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Campos bloqueados: Título, Imagen, Año, Edición, Nº Copias y Dimensiones.</p>
                    @else
                        <div class="flex items-center gap-2 text-amber-700">
                            <span class="inline-flex px-2 py-1 bg-amber-50 border border-amber-200 rounded text-xs font-semibold">QR pendiente</span>
                            <span class="text-xs text-gray-600">Valídalo desde la lista de obras con “Validar QR”.</span>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Ve a <a href="{{ route('artworks.index') }}" class="underline">Obras</a> y usa la acción <strong>Validar QR</strong> en la fila de esta obra.</p>
                    @endif
                </div>
            </div>

            <!-- Exhibitions -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-900">{{ __('Exhibitions') }}</h3>
                        <a href="{{ route('exhibitions.create', $artwork) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">{{ __('+ Add exhibition') }}</a>
                    </div>

                    @if ($artwork->exhibitions->isEmpty())
                        <p class="mt-4 text-sm text-gray-500">{{ __('No exhibitions yet.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach ($artwork->exhibitions as $exhibition)
                                <li class="py-3 flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $exhibition->name }}</p>
                                        @if ($exhibition->start_date)
                                            <p class="text-xs text-gray-500">{{ __('Fecha de inicio') }}: {{ $exhibition->start_date->format('Y-m-d') }}</p>
                                        @endif
                                        @if ($exhibition->end_date)
                                            <p class="text-xs text-gray-500">{{ __('Fecha de fin') }}: {{ $exhibition->end_date->format('Y-m-d') }}</p>
                                        @endif
                                        @if ($exhibition->location)
                                            <p class="text-xs text-gray-500">{{ __('Ubicación') }}: {{ $exhibition->location }}</p>
                                        @endif
                                        @if ($exhibition->description)<p class="text-sm text-gray-600">{{ $exhibition->description }}</p>@endif
                                        @if ($exhibition->links)<p class="text-xs text-indigo-600 truncate">{{ $exhibition->links }}</p>@endif
                                    </div>
                                    <form method="POST" action="{{ route('exhibitions.destroy', $exhibition) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Links externos -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-900">{{ __('Enlaces externos') }}</h3>
                        <span class="text-xs text-gray-500">{{ $artwork->links->count() }}/10</span>
                    </div>

                    @if ($artwork->links->isEmpty())
                        <p class="mt-4 text-sm text-gray-500">{{ __('No hay enlaces todavía.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach ($artwork->links as $link)
                                <li class="py-2 flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <span class="text-xs uppercase tracking-wide text-gray-500">{{ __('Enlace') }} {{ $link->type }}</span>
                                        <a href="{{ $link->url }}" target="_blank" rel="noopener" class="block text-sm text-indigo-600 hover:underline truncate">{{ $link->url }}</a>
                                    </div>
                                    <form method="POST" action="{{ route('artwork-links.destroy', $link) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($artwork->links->count() < 10)
                        <form method="POST" action="{{ route('artwork-links.store', $artwork) }}" class="mt-4 flex flex-wrap items-end gap-2">
                            @csrf
                            <div class="flex flex-col">
                                <label class="text-xs text-gray-500">{{ __('Tipo') }}</label>
                                <select name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm mt-1">
                                    <option value="video">{{ __('Video') }}</option>
                                    <option value="photo">{{ __('Foto') }}</option>
                                    <option value="blog">{{ __('Blog') }}</option>
                                </select>
                            </div>
                            <div class="flex-1 min-w-[16rem] flex flex-col">
                                <label class="text-xs text-gray-500">{{ __('URL') }}</label>
                                <x-text-input type="url" name="url" placeholder="https://..." class="block mt-1 w-full" required />
                            </div>
                            <x-secondary-button>{{ __('Agregar enlace') }}</x-secondary-button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- COA -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-900">COA — {{ __('Certificado de Autenticidad') }}</h3>
                        <span class="text-xs text-gray-500">{{ auth()->user()->tokenBalance() }} tokens</span>
                    </div>
                    @php $certs = $artwork->certificates; $hasSig = (bool) auth()->user()->signature_image; $validCert = $certs->firstWhere('status','valid'); @endphp
                    @if($certs->isEmpty())
                        <p class="mt-2 text-sm text-gray-500">{{ __('Aún no hay COA para esta obra. Genera uno bloqueado con nº consecutivo, hash y PDF reimprimible.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach($certs as $c)
                                <li class="py-3 flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-mono font-semibold">{{ $c->certificate_number }} <span class="text-xs {{ $c->status==='valid'?'text-emerald-600':'text-red-600' }}">{{ $c->status }}</span></p>
                                        <p class="text-xs text-gray-500">{{ $c->issued_at->format('d/m/Y H:i') }} · {{ $c->holder_name }} @if($c->include_signature) · con firma @else · espacio firma @endif</p>
                                        @if($c->custom_text)<p class="text-xs text-gray-600 italic">“{{ \Illuminate\Support\Str::limit($c->custom_text, 80) }}”</p>@endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('certificates.download', [$artwork, $c]) }}" target="_blank" class="text-sm text-indigo-600 hover:underline">{{ __('Descargar') }}</a>
                                        <a href="{{ route('certificates.verify', $c->certificate_number) }}" target="_blank" class="text-sm text-gray-600 hover:underline">{{ __('Verificar') }}</a>
                                        @if($c->status==='valid')
                                        <form method="POST" action="{{ route('certificates.revoke', [$artwork, $c]) }}" onsubmit="return confirm('¿Revocar COA?');">@csrf<button class="text-sm text-red-600 hover:text-red-800">{{ __('Revocar') }}</button></form>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if(!$artwork->isQrValidated())
                        <p class="mt-4 text-sm text-amber-700">Valida los 6 campos y genera el QR definitivo para poder emitir el COA.</p>
                    @elseif($validCert)
                        <div x-data="{ open:false, customText: @js($validCert->custom_text ?? $artwork->description ?? '') }" class="mt-4">
                            <button @click="open=!open" class="px-4 py-2 bg-white border border-gray-300 text-sm rounded hover:bg-gray-50">{{ __('Editar Texto Descriptivo') }}</button>
                            <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div class="fixed inset-0 bg-brand-800/60" @click="open=false"></div>
                                    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg p-6 my-8">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="font-semibold text-lg text-gray-900">{{ __('Editar Texto Descriptivo') }}</h3>
                                            <button @click="open=false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                                        </div>
                                        <form method="POST" action="{{ route('certificates.update', [$artwork, $validCert]) }}">
                                            @csrf @method('PATCH')
                                            <div>
                                                <label class="text-xs font-medium text-gray-700">{{ __('Texto Descriptivo (máx 800)') }}</label>
                                                <textarea name="custom_text" x-model="customText" rows="3" maxlength="800" class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="{{ __('Nota del artista para este COA...') }}">{{ old('custom_text', $validCert->custom_text ?? $artwork->description ?? '') }}</textarea>
                                                <p class="mt-1 text-xs text-gray-400">{{ __('Mismo COA, mismo QR y hash. Solo cambia el texto y se regenera el PDF.') }}</p>
                                            </div>
                                            <div class="mt-4 flex items-center justify-end gap-2">
                                                <button type="button" @click="open=false" class="underline text-sm text-gray-600 hover:text-gray-900">{{ __('Cancelar') }}</button>
                                                <button type="button" @click="window.open('{{ route('certificates.preview', $artwork) }}?custom_text='+encodeURIComponent(customText)+'&include_signature='+( {{ $validCert->include_signature ? '1' : '0' }} ), '_blank')" class="px-4 py-2 bg-white border border-gray-300 text-sm rounded hover:bg-gray-50">{{ __('Vista previa') }}</button>
                                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded hover:bg-emerald-700">{{ __('Guardar') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                    <div x-data="{ open:false, customText: @js($artwork->description ?? ''), includeSig:false }" class="mt-4">
                        <button @click="open=!open" class="px-4 py-2 bg-brand text-white text-sm rounded hover:bg-brand-700">{{ __('Generar COA') }}</button>
                        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="fixed inset-0 bg-brand-800/60" @click="open=false"></div>
                                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg p-6 my-8">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="font-semibold text-lg text-gray-900">{{ __('Generar COA') }}</h3>
                                        <button @click="open=false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                                    </div>
                            <form method="POST" action="{{ route('certificates.store', $artwork) }}">
                                @csrf
                                <div>
                                    <label class="text-xs font-medium text-gray-700">{{ __('Texto Descriptivo (máx 800)') }}</label>
                                    <textarea name="custom_text" x-model="customText" rows="3" maxlength="800" class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="{{ __('Nota del artista para este COA...') }}">{{ old('custom_text', $artwork->description ?? '') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-400">{{ __('Por defecto la descripción de la obra. Puedes editarla.') }}</p>
                                </div>
                                @if($hasSig)
                                <label class="mt-3 flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="include_signature" value="1" x-model="includeSig" class="rounded border-gray-300 text-indigo-600"> {{ __('Incluir mi firma digitalizada') }} <img src="{{ auth()->user()->signatureUrl() }}" class="h-8 border bg-white p-1">
                                    <span class="text-xs text-gray-500">({{ __('o deja espacio libre si no marcas') }})</span>
                                </label>
                                @else
                                <p class="mt-3 text-xs text-amber-700">{{ __('Sin firma subida: el COA dejará espacio libre. Súbela en Perfil si quieres incluirla.') }} <a href="{{ route('profile.edit') }}" class="underline">Perfil</a></p>
                                @endif
                                <label class="mt-3 flex gap-2 items-start text-xs text-gray-700">
                                    <input type="checkbox" name="accept_terms" value="1" required class="mt-1 rounded border-gray-300 text-indigo-600">
                                    <span>{{ __('Declaro que soy autor/titular legítimo y que la información del COA es veraz. Entiendo que QRTE solo certifica el registro y hash al emitir y provee verificación online.') }}</span>
                                </label>
                                @php $coaFunc = \App\Models\TokenFunction::where('name','Generar Certificado de Autenticidad (COA)')->first(); $coaCost = $coaFunc?->tokens ?? 1; @endphp
                                <p class="mt-2 text-xs text-gray-500">{{ $coaCost==0 ? __('Gratis en promoción (0 tokens)') : __('Costo: :cost token· El COA queda bloqueado y reimprimible con nº consecutivo.', ['cost'=>$coaCost]) }}</p>
                                <div class="mt-4 flex items-center justify-end gap-2">
                                    <button type="button" @click="open=false" class="underline text-sm text-gray-600 hover:text-gray-900">{{ __('Cancelar') }}</button>
                                    <button type="button" @click="window.open('{{ route('certificates.preview', $artwork) }}?custom_text='+encodeURIComponent(customText)+'&include_signature='+(includeSig?1:0), '_blank')" class="px-4 py-2 bg-white border border-gray-300 text-sm rounded hover:bg-gray-50">{{ __('Vista previa') }}</button>
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded hover:bg-emerald-700">{{ $coaCost==0 ? __('Generar COA gratis') : __('Generar y consumir token') }}</button>
                                </div>
                            </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ownership -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-900">{{ __('Ownership / Provenance') }}</h3>
                        <a href="{{ route('ownerships.create', $artwork) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">{{ __('+ Add ownership') }}</a>
                    </div>

                    @if ($artwork->ownerships->isEmpty())
                        <p class="mt-4 text-sm text-gray-500">{{ __('No ownership records yet.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach ($artwork->ownerships as $ownership)
                                <li class="py-3">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <span class="text-xs uppercase tracking-wide text-gray-500">{{ $ownership->type === 'transfer' ? __('Transfer') : __('Initial') }}</span>
                                            @if ($ownership->type === 'transfer')
                                                <p class="text-sm text-gray-700">{{ __('🔒 Propietario protegido') }}</p>
                                            @else
                                                <p class="text-sm text-gray-900">{{ $ownership->owner_name }}</p>
                                                @if ($ownership->owner_email)<p class="text-xs text-gray-500">{{ $ownership->owner_email }}</p>@endif
                                            @endif
                                            @if ($ownership->transferred_at)<p class="text-xs text-gray-500">{{ $ownership->transferred_at->format('Y-m-d') }}</p>@endif
                                            @if ($ownership->notes)<p class="text-xs text-gray-500">{{ $ownership->notes }}</p>@endif
                                        </div>
                                        <form method="POST" action="{{ route('ownerships.destroy', $ownership) }}" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                    @if ($ownership->type === 'transfer')
                                        <form method="POST" action="{{ route('ownerships.reveal', $ownership) }}" class="mt-2 flex items-center gap-2">
                                            @csrf
                                            <x-text-input type="text" name="secret_key" placeholder="{{ __('Secret key') }}" class="block w-64" />
                                            <x-secondary-button>{{ __('Reveal') }}</x-secondary-button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
