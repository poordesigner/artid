<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $artist->name }} — QRTE</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Space+Grotesk:300,400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #333333; color: #f5f5f5; font-family: 'Space Grotesk', system-ui, sans-serif; line-height: 1.6; -webkit-font-smoothing: antialiased; }
        .container { max-width: 720px; margin: 0 auto; padding: 24px 16px 64px; }
        .avatar { width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 2px solid #4a4a4a; background: #242424; }
        .name { font-size: 1.8rem; font-weight: 600; margin-top: 16px; line-height: 1.2; }
        .verified { display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; font-size: 0.8rem; color: #22c55e; border: 1px solid #333333; background: #242424; border-radius: 9999px; padding: 4px 12px; }
        .section { margin-top: 32px; }
        .section h2 { font-size: 0.85rem; color: #ff0066; text-transform: uppercase; letter-spacing: 0.12em; border-bottom: 1px solid #4a4a4a; padding-bottom: 8px; }
        .statement { margin-top: 16px; color: #ccc; white-space: pre-line; font-size: 0.95rem; }
        .links { list-style: none; margin-top: 12px; display: flex; flex-wrap: wrap; gap: 10px; }
        .links a { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border: 1px solid #4a4a4a; border-radius: 9999px; color: #f5f5f5; text-decoration: none; font-size: 0.85rem; transition: border-color 0.2s, color 0.2s; }
        .links a:hover { border-color: #ff0066; color: #ff0066; }
        .links a svg { width: 16px; height: 16px; fill: currentColor; }
        .cv-link { display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; padding: 8px 16px; background: #242424; border: 1px solid #4a4a4a; border-radius: 8px; color: #f5f5f5; text-decoration: none; font-size: 0.85rem; transition: border-color 0.2s; }
        .cv-link:hover { border-color: #ff0066; }
        .artworks-count { margin-top: 8px; font-size: 0.85rem; color: #888; }
        .footer { margin-top: 40px; text-align: center; font-size: 0.75rem; color: #555; }
        .footer a { color: #888; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        @if ($artist->avatar)
            <img class="avatar" src="{{ $artist->avatarUrl() }}" alt="{{ $artist->name }}">
        @else
            <div class="avatar" style="display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:600;color:#ff0066;">{{ strtoupper(substr($artist->name, 0, 1)) }}</div>
        @endif

        <h1 class="name">{{ $artist->name }}</h1>
        <div class="verified">&#10003; QRTE</div>
        <p class="artworks-count">{{ $artist->artworks_count }} {{ $artist->artworks_count === 1 ? 'obra' : 'obras' }}</p>

        @if ($artist->statement)
            <section class="section">
                <h2>Declaración</h2>
                <p class="statement">{{ $artist->statement }}</p>
            </section>
        @endif

        @php
            $socialNetworks = ['instagram', 'behance', 'artstation', 'youtube', 'tiktok'];
            $socialLinks = collect($socialNetworks)->map(fn ($n) => ['network' => $n, 'url' => $artist->socialUrl($n)])->filter(fn ($s) => $s['url']);
        @endphp

        @if ($socialLinks->isNotEmpty() || $artist->website_url || $artist->cv_pdf)
            <section class="section">
                <h2>Enlaces</h2>
                <ul class="links">
                    @if ($artist->website_url)
                        <li><a href="{{ $artist->website_url }}" target="_blank" rel="noopener">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            {{ __('Página web') }}
                        </a></li>
                    @endif
                    @foreach ($socialLinks as $s)
                        <li><a href="{{ $s['url'] }}" target="_blank" rel="noopener">{{ ucfirst($s['network']) }}</a></li>
                    @endforeach
                </ul>

                @if ($artist->cv_pdf)
                    <a class="cv-link" href="{{ $artist->cvUrl() }}" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        {{ __('Hoja de vida (PDF)') }}
                    </a>
                @endif
            </section>
        @endif

        @if ($artist->links->isNotEmpty())
            <section class="section">
                <h2>Portafolio / CV / Exposiciones</h2>
                <ul class="links">
                    @foreach ($artist->links as $link)
                        <li><a href="{{ $link->url }}" target="_blank" rel="noopener">{{ ucfirst($link->type) }}</a></li>
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
