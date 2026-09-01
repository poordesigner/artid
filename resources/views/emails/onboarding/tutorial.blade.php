<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('¿Necesitás ayuda?') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; color: #1f2937; margin: 0; padding: 0; background: #f3f4f6; }
        .wrap { max-width: 560px; margin: 24px auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        .head { padding: 24px 32px; background: #1c1917; color: #ffffff; }
        .head h1 { margin: 0; font-size: 18px; font-weight: 600; }
        .head span { display: block; margin-top: 4px; font-size: 12px; color: #d1d5db; }
        .body { padding: 32px; }
        .msg { font-size: 15px; line-height: 1.7; margin: 16px 0; }
        .btn { display: inline-block; margin-top: 16px; padding: 12px 24px; background: #4f46e5; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; }
        .btn-secondary { display: inline-block; margin-top: 8px; padding: 12px 24px; background: #ffffff; color: #4f46e5 !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; border: 2px solid #4f46e5; }
        .steps { margin: 20px 0; padding: 0; list-style: none; counter-reset: step; }
        .steps li { counter-increment: step; padding: 12px 0 12px 48px; position: relative; font-size: 15px; border-bottom: 1px solid #f3f4f6; }
        .steps li::before { content: counter(step); position: absolute; left: 0; top: 12px; width: 32px; height: 32px; background: #eef2ff; color: #4f46e5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; }
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
                <p>{{ __('¿Necesitás ayuda para empezar? Crear tu primera obra es muy fácil.') }}</p>
                <p>{{ __('Estos son los pasos:') }}</p>
                <ol class="steps">
                    <li>{{ __('Elegí un título, año y técnica para tu obra') }}</li>
                    <li>{{ __('Subí una imagen (se optimiza automáticamente)') }}</li>
                    <li>{{ __('Obtené tu QR único y la ficha pública verificada') }}</li>
                    <li>{{ __('Compartí el link con galerías, coleccionistas o en ferias') }}</li>
                </ol>
                <a class="btn" href="{{ url('/artworks/create') }}">{{ __('Creá tu primera obra') }}</a>
                <br>
                <a class="btn-secondary" href="{{ url('/ayuda') }}">{{ __('Visitá nuestro Centro de Ayuda') }}</a>
            </div>
        </div>
        <div class="foot">
            **QRTE** — {{ __('Identidad digital para obras de arte') }} · POORdesigner.com
        </div>
    </div>
</body>
</html>
