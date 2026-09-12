<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Comprá más tokens') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; color: #1f2937; margin: 0; padding: 0; background: #f3f4f6; }
        .wrap { max-width: 560px; margin: 24px auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        .head { padding: 24px 32px; background: #1c1917; color: #ffffff; }
        .head h1 { margin: 0; font-size: 18px; font-weight: 600; }
        .head span { display: block; margin-top: 4px; font-size: 12px; color: #d1d5db; }
        .body { padding: 32px; }
        .msg { font-size: 15px; line-height: 1.7; margin: 16px 0; }
        .btn { display: inline-block; margin-top: 16px; padding: 12px 24px; background: #4f46e5; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; }
        .warning { background: #fef2f2; padding: 16px; border-radius: 8px; margin: 16px 0; border-left: 4px solid #ef4444; }
        .features { margin: 20px 0; }
        .features li { padding: 8px 0; font-size: 15px; list-style: none; }
        .features li::before { content: "✓"; color: #10b981; font-weight: 700; margin-right: 8px; }
        .foot { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="head">
            <h1>{{ __('Hola, :name', ['name' => $artist->name]) }}</h1>
            <span>QRTE — {{ __('Identidad digital para obras de arte') }}</span>
        </div>
        <div class="body">
            <div class="msg">
                <div class="warning">
                    <strong>{{ __('Te quedan :count tokens.', ['count' => $artist->tokens_balance]) }}</strong><br>
                    {{ __('Para seguir creando obras, necesitás más tokens.') }}
                </div>
                <p>{{ __('Cada token te permite registrar una obra con:') }}</p>
                <ul class="features">
                    <li>{{ __('QR permanente y único') }}</li>
                    <li>{{ __('Ficha pública verificada') }}</li>
                    <li>{{ __('Metadata, historial y proveniencia') }}</li>
                    <li>{{ __('Control cifrado de propiedad') }}</li>
                </ul>
                <p>{{ __('Comprá un paquete de tokens y seguí creando.') }}</p>
                <a class="btn" href="{{ url('/planes') }}">{{ __('Ver paquetes de tokens') }}</a>
            </div>
        </div>
        <div class="foot">
            **QRTE** — {{ __('Identidad digital para obras de arte') }} · POORdesigner.com
        </div>
    </div>
</body>
</html>
