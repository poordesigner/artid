<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: 'Helvetica', sans-serif; color: #1a1a1a; font-size: 12px; line-height: 1.45; }
.header { text-align: center; border-bottom: 3px solid #333; padding-bottom: 12px; margin-bottom: 14px; }
.header h1 { font-size: 20px; letter-spacing: 0.2em; margin: 0; }
.header p { font-size: 10px; color: #666; margin: 6px 0 0; }
.divider { border: none; border-top: 1px solid #ddd; margin: 14px 0; }
.grid2 { display: table; width: 100%; border-collapse: collapse; }
.col { display: table-cell; vertical-align: top; padding: 6px; }
.col-50 { width: 50%; }
.box { border: 1px solid #ddd; padding: 12px; margin-bottom: 10px; }
.label { font-size: 8px; text-transform: uppercase; letter-spacing: 0.15em; color: #888; }
.value { font-size: 13px; font-weight: bold; margin-top: 3px; }
.meta { font-size: 11px; margin-top: 6px; line-height: 1.4; }
.hash { font-family: monospace; font-size: 7px; word-break: break-all; color: #555; }
.signature-box { border: 1.5px dashed #aaa; height: 80px; display: flex; align-items: center; justify-content: center; text-align: center; padding: 8px; }
.qr-img { width: 160px; height: 160px; margin: 8px auto; display: block; }
.footer { margin-top: 14px; border-top: 1px solid #ddd; padding-top: 10px; font-size: 8px; color: #888; text-align: center; }
.footer strong { font-size: 10px; color: #333; }
.watermark { position: fixed; top: 42%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 68px; color: rgba(220,38,38,0.18); font-weight: bold; letter-spacing: 0.25em; border: 5px solid rgba(220,38,38,0.18); padding: 16px 32px; z-index: 1000; }
</style>
</head>
<body>
<div class="header">
    <h1>CERTIFICADO DE AUTENTICIDAD</h1>
    <p style="font-size:13px; font-weight:bold; letter-spacing:0.12em; margin-top:6px; color:#111;">{{ $cert->certificate_number }}</p>
    <p style="font-size:10px; color:#666; margin-top:2px;">{{ $cert->issued_at->format('d/m/Y') }} @if($cert->issued_location) — {{ $cert->issued_location }} @endif</p>
    @if(!empty($isPreview ?? false))
        <p style="margin-top:6px; font-size:10px; color:#b45309; background:#fef3c7; display:inline-block; padding:2px 8px; border-radius:999px;">VISTA PREVIA — no válido hasta generar definitivo</p>
    @endif
</div>
<hr class="divider">

@if($cert->status==='revoked')
<div class="watermark">REVOCADO</div>
@endif

<!-- Fila 1: Imagen | Obra (misma altura, título agrandado) -->
<div class="grid2">
    <div class="col col-50">
        @if(!empty($artworkBase64 ?? null))
            <div class="box" style="text-align:center; height: 260px; display:flex; align-items:center; justify-content:center;">
                <img src="data:{{ $artworkMime }};base64,{{ $artworkBase64 }}" style="max-width:100%; max-height:230px;">
            </div>
        @elseif($artwork->imageUrl())
            <div class="box" style="text-align:center; height: 260px; display:flex; align-items:center; justify-content:center;">
                <img src="{{ $artwork->imageUrl() }}" style="max-width:100%; max-height:230px;">
            </div>
        @else
            <div class="box" style="text-align:center; height: 260px; display:flex; align-items:center; justify-content:center; color:#999;">sin imagen</div>
        @endif
    </div>
    <div class="col col-50">
        <div class="box" style="height: 260px;">
            <div class="label">Obra</div>
            <div class="value" style="font-size:15px;">{{ $artwork->title }}</div>
            <div class="meta">
                <div>Año: {{ $artwork->year ?? '—' }}</div>
                <div>Edición: {{ $artwork->edition ?? '—' }} @if($artwork->edition_number) ({{ $artwork->edition_number }}/{{ trim(str_replace('1/','',$artwork->edition)) }}) @endif</div>
                <div>Serie: {{ $artwork->series ?? '—' }}</div>
                <div>Técnica: {{ $artwork->technique ?? '—' }}</div>
                <div>Dimensiones: {{ $artwork->dimensions ?? '—' }}</div>
                <div>Lugar de creación: {{ $artwork->creation_location ?? '—' }}</div>
                <div>ID permanente: {{ $artwork->artwork_id }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Fila 2: Nota del artista (ancho completo) -->
<div class="box">
    <div class="label">Texto Descriptivo</div>
            @if($cert->custom_text)
                <p style="font-style:italic; margin-top:6px; font-size:11px;">{{ $cert->custom_text }}</p>
            @else
                <p style="margin-top:6px; font-size:11px; color:#999;">—</p>
            @endif
</div>

<!-- Fila 3: QRs — compacto -->
<div class="grid2">
    <div class="col col-50">
        <div class="box" style="text-align:center;">
            <div class="label">QR-FICHA PÚBLICA</div>
            @if(!empty($qrFichaBase64 ?? $qrBase64 ?? null))
                <img src="data:image/svg+xml;base64,{{ $qrFichaBase64 ?? $qrBase64 }}" style="width:140px; height:140px; margin:6px auto; display:block;">
            @else
                <p style="font-size:10px; color:#999; padding:12px;">QR no disponible</p>
            @endif
        </div>
    </div>
    <div class="col col-50">
        <div class="box" style="text-align:center;">
            <div class="label">QR-VERIFICACIÓN COA</div>
            @if(!empty($qrVerifyBase64 ?? null))
                <img src="data:image/svg+xml;base64,{{ $qrVerifyBase64 }}" style="width:140px; height:140px; margin:6px auto; display:block;">
            @else
                <p style="font-size:10px; color:#999; padding:12px;">QR no disponible</p>
            @endif
        </div>
    </div>
</div>
<!-- Fila 3b: Fingerprint centrado -->
<div class="box" style="text-align:center;">
    <div class="label">Fingerprint COA</div>
    @if(!empty($isPreview ?? false))
        <div class="hash" style="margin-top:4px; color:#b45309; font-size:9px;">PREVIEW — hash no definitivo</div>
    @else
        <div class="hash" style="margin-top:4px; font-size:9px; letter-spacing:0.05em;">{{ $hashCombined ?? '' }}</div>
    @endif
</div>

<!-- Fila 4: Certificación + Firma (ancho completo) -->
<div class="box" style="text-align:center;">
    <p style="font-size:12px; line-height:1.5; margin:0;">Yo, <strong>{{ $artist->name }}</strong>, certifico y garantizo la autenticidad, creación y elaboración de la presente obra.</p>
    <div style="margin-top:14px;">
        @if($cert->include_signature && !empty($sigBase64 ?? null))
            <img src="data:{{ $sigMime }};base64,{{ $sigBase64 }}" style="max-height:60px; max-width: 260px; margin: 0 auto; display:block;">
        @elseif($cert->include_signature && $artist->signature_image)
            <img src="{{ $artist->signatureUrl() }}" style="max-height:60px; max-width:260px; margin: 0 auto; display:block;">
        @else
            <div class="signature-box" style="height:70px; max-width: 320px; margin: 0 auto;"></div>
        @endif
        <div style="margin-top:6px; font-size:9px; color:#666;">{{ $artist->name }} — {{ $cert->issued_at->format('d/m/Y') }}</div>
    </div>
</div>

<hr class="divider">
<div class="footer">
    CERTIFICADO GENERADO POR QRTE<br>
    <strong>qrte.poordesigner.com</strong>
    @if($cert->status==='revoked')
        <br><span style="color:#dc2626; font-weight:bold;">REVOCADO {{ $cert->revoked_at->format('d/m/Y H:i') }}</span>
    @endif
</div>
</body>
</html>
