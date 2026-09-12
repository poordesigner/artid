@extends('layouts.public')
@section('title', 'Verificación COA ' . $cert->certificate_number)
@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto px-6">
        <div class="bg-white shadow-sm rounded-lg p-8">
            <p class="text-xs uppercase tracking-widest text-gray-400">QRTE — Verificación</p>
            <h1 class="mt-2 text-2xl font-bold">COA {{ $cert->certificate_number }}</h1>
            <p class="mt-1 text-sm {{ $cert->status==='valid' ? 'text-emerald-600' : 'text-red-600' }} font-semibold">{{ $cert->status==='valid' ? 'Válido' : 'Revocado' }} — emitido {{ $cert->issued_at->format('d/m/Y H:i') }}</p>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    @if($cert->artwork->imageUrl())
                        <img src="{{ $cert->artwork->imageUrl() }}" alt="{{ $cert->artwork->title }}" class="w-full h-64 object-contain rounded border border-gray-200 bg-white">
                    @endif
                    <p class="mt-3 text-xs uppercase text-gray-400">Obra</p>
                    <p class="font-semibold">{{ $cert->artwork->title }} ({{ $cert->artwork->artwork_id }})</p>
                    <p class="text-sm text-gray-600">{{ $cert->artwork->year }} · {{ $cert->artwork->technique }}</p>
                    <a href="{{ $cert->artwork->signedUrl() }}" target="_blank" class="mt-3 inline-flex text-sm text-brand hover:underline">Ver ficha técnica →</a>
                </div>
                <div>
                    @if($cert->artist->avatarUrl())
                        <img src="{{ $cert->artist->avatarUrl() }}" alt="{{ $cert->artist->name }}" class="h-16 w-16 rounded-full object-cover border border-gray-200">
                    @endif
                    <p class="mt-3 text-xs uppercase text-gray-400">Artista</p>
                    <p class="font-semibold">{{ $cert->artist->name }}</p>
                    <p class="text-sm text-gray-600">{{ $cert->artwork->creation_location ?? '—' }}</p>
                    <a href="{{ route('public.artist', $cert->artist->id) }}" target="_blank" class="mt-3 inline-flex text-sm text-brand hover:underline">Ver perfil del artista →</a>
                </div>
            </div>

            <div class="mt-6 p-4 bg-gray-50 rounded text-xs font-mono break-all">
                <div>Fingerprint COA: {{ $hashCombined }}</div>
                <div class="mt-1 text-[11px] text-gray-500">Este valor debe coincidir con el Fingerprint impreso en su Certificado.</div>
            </div>
        </div>
    </div>
</div>
@endsection
