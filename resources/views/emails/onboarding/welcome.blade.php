<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('¡Bienvenido a QRTE!') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; color: #1f2937; margin: 0; padding: 0; background: #f3f4f6; }
        .wrap { max-width: 560px; margin: 24px auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        .head { padding: 24px 32px; background: #1c1917; color: #ffffff; }
        .head h1 { margin: 0; font-size: 18px; font-weight: 600; }
        .head span { display: block; margin-top: 4px; font-size: 12px; color: #d1d5db; }
        .body { padding: 32px; }
        .msg { font-size: 15px; line-height: 1.7; margin: 16px 0; }
        .btn { display: inline-block; margin-top: 16px; padding: 12px 24px; background: #4f46e5; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; }
        .tokens { display: inline-block; margin-top: 16px; padding: 12px 24px; background: #ecfdf5; color: #065f46; border-radius: 8px; font-weight: 600; font-size: 14px; }
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
                <p>{{ __('¡Bienvenido a QRTE! Tu cuenta está lista para empezar.') }}</p>
                <p>{{ __('Con QRTE podés registrar tus obras de arte y obtener un código QR permanente que apunta a una ficha pública verificada con metadata, historial y proveniencia.') }}</p>
                <div class="tokens">{{ __('Recibís :count tokens gratis para crear tus primeras obras.', ['count' => config('artid.welcome_tokens', 5)]) }}</div>
                <p style="margin-top: 24px;">{{ __('¿Qué podés hacer ahora?') }}</p>
                <ul style="font-size: 15px; line-height: 1.8; padding-left: 20px;">
                    <li>{{ __('Creá tu primera obra en segundos') }}</li>
                    <li>{{ __('Obtené un QR único y permanente para cada obra') }}</li>
                    <li>{{ __('Compartí la ficha pública con coleccionistas y galerías') }}</li>
                </ul>
                <a class="btn" href="{{ url('/artworks/create') }}">{{ __('Creá tu primera obra') }}</a>
            </div>
        </div>
        <div class="foot">
            **QRTE** — {{ __('Identidad digital para obras de arte') }} · POORdesigner.com
        </div>
    </div>
</body>
</html>
