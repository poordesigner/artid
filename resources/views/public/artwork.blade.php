<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $artwork->title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Space+Grotesk:300,400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #333333; color: #f5f5f5; font-family: 'Space Grotesk', system-ui, sans-serif; line-height: 1.6; -webkit-font-smoothing: antialiased; }
        .container { max-width: 720px; margin: 0 auto; padding: 24px 16px 64px; }
        .image { width: 100%; max-height: 60vh; object-fit: contain; border-radius: 8px; background: #242424; }
        .title { font-size: 2rem; font-weight: 600; margin-top: 20px; line-height: 1.2; }
        .artist { color: #ff0066; margin-top: 4px; font-weight: 500; }
        .verified { display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; font-size: 0.8rem; color: #22c55e; border: 1px solid #333333; background: #242424; border-radius: 9999px; padding: 4px 12px; }
        .meta { list-style: none; margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; }
        .meta li span { color: #888; display: block; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; }
        .meta li strong { font-weight: 500; color: #f0f0f0; word-break: break-word; }
        .description { margin-top: 20px; color: #ccc; white-space: pre-line; }
        .section { margin-top: 36px; }
        .section h2 { font-size: 0.85rem; color: #ff0066; text-transform: uppercase; letter-spacing: 0.12em; border-bottom: 1px solid #4a4a4a; padding-bottom: 8px; }
        .events { list-style: none; margin-top: 8px; }
        .event { padding: 14px 0; border-bottom: 1px solid #4a4a4a; }
        .event-name { font-weight: 500; }
        .event-type { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #888; }
        .owner { margin-top: 2px; font-weight: 500; }
        .owner.locked { color: #ff0066; }
        .event-date { font-size: 0.8rem; color: #666; margin-top: 2px; }
        .event-desc { margin-top: 6px; color: #bbb; font-size: 0.9rem; white-space: pre-line; }
        .event-link { display: inline-block; margin-top: 6px; color: #ff0066; font-size: 0.85rem; text-decoration: none; }
        .links { list-style: none; margin-top: 12px; display: flex; flex-wrap: wrap; gap: 8px; }
        .link-tag { display: inline-block; font-size: 0.8rem; color: #eee; border: 1px solid #2a2a2a; border-radius: 9999px; padding: 5px 14px; text-decoration: none; }
        .link-tag:hover { border-color: #ff0066; color: #ff0066; }
        .link-tag small { display: inline-block; margin-left: 8px; color: #888; text-transform: uppercase; font-size: 0.62rem; letter-spacing: 0.08em; }
        .footer { margin-top: 40px; text-align: center; font-size: 0.75rem; color: #555; }
        .footer a { color: #888; text-decoration: none; }
        @media (max-width: 480px) { .meta { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        @if ($artwork->imageUrl())
            <img class="image" src="{{ $artwork->imageUrl() }}" alt="{{ $artwork->title }}">
        @endif

        <h1 class="title">{{ $artwork->title }}</h1>
        <p class="artist"><a href="{{ route('public.artist', $artwork->artist_id) }}" style="color:inherit;text-decoration:none;">{{ '@'.($artwork->artist->name) }}</a></p>

        <div class="verified">&#10003; Verificado por QRTE</div>

        <ul class="meta">
            @if ($artwork->year)<li><span>Año</span><strong>{{ $artwork->year }}</strong></li>@endif
            @if ($artwork->edition)<li><span>Edición</span><strong>{{ $artwork->edition }}</strong></li>@endif
            @if ($artwork->series)<li><span>Serie</span><strong>{{ $artwork->series }}</strong></li>@endif
            @if ($artwork->technique)<li><span>Técnica</span><strong>{{ $artwork->technique }}</strong></li>@endif
            @if ($artwork->dimensions)<li><span>Dimensiones</span><strong>{{ $artwork->dimensions }}</strong></li>@endif
        </ul>

        @if ($artwork->description)
            <p class="description">{{ $artwork->description }}</p>
        @endif

        @if ($artwork->links->isNotEmpty())
            <section class="section">
                <h2>Enlaces</h2>
                <ul class="links">
                    @foreach ($artwork->links as $link)
                        <li><a class="link-tag" href="{{ $link->url }}" target="_blank" rel="noopener">{{ ucfirst($link->type) }}<small>&#8599;</small></a></li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($artwork->exhibitions->isNotEmpty())
            <section class="section">
                <h2>Exposiciones</h2>
                <ul class="events">
                    @foreach ($artwork->exhibitions as $exhibition)
                        <li class="event">
                            <div class="event-name">{{ $exhibition->name }}</div>
                            @if ($exhibition->start_date)
                                <div class="event-date">{{ __('Inicio') }}: {{ $exhibition->start_date->format('Y-m-d') }}</div>
                            @endif
                            @if ($exhibition->end_date)
                                <div class="event-date">{{ __('Fin') }}: {{ $exhibition->end_date->format('Y-m-d') }}</div>
                            @endif
                            @if ($exhibition->location)
                                <div class="event-date">{{ __('Ubicación') }}: {{ $exhibition->location }}</div>
                            @endif
                            @if ($exhibition->description)<div class="event-desc">{{ $exhibition->description }}</div>@endif
                            @if ($exhibition->links)<a class="event-link" href="{{ $exhibition->links }}" target="_blank" rel="noopener">Enlace</a>@endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($artwork->ownerships->isNotEmpty())
            <section class="section">
                <h2>Proveniencia</h2>
                <ul class="events">
                    @foreach ($artwork->ownerships as $ownership)
                        <li class="event">
                            <div class="event-type">{{ $ownership->type === 'transfer' ? 'Transferencia / Venta' : 'Propietario inicial' }}</div>
                            @if ($ownership->type === 'transfer')
                                <div class="owner locked">&#128274; Propietario protegido</div>
                            @else
                                <div class="owner">{{ $ownership->owner_name }}</div>
                            @endif
                            @if ($ownership->transferred_at)<div class="event-date">{{ $ownership->transferred_at->format('Y-m-d') }}</div>@endif
                            @if ($ownership->notes)<div class="event-desc">{{ $ownership->notes }}</div>@endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        <div class="footer">
            <a href="{{ config('QRTE.public_url') }}" target="_blank" rel="noopener">QRTE</a> — by POORdesigner.com
        </div>
    </div>
</body>
</html>
