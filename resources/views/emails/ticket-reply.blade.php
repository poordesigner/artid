<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $ticket->number }} — {{ $ticket->subject }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; color: #1f2937; margin: 0; padding: 0; background: #f3f4f6; }
        .wrap { max-width: 560px; margin: 24px auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        .head { padding: 24px 32px; background: #1c1917; color: #ffffff; }
        .head h1 { margin: 0; font-size: 18px; font-weight: 600; }
        .head span { display: block; margin-top: 4px; font-size: 12px; color: #d1d5db; }
        .body { padding: 32px; }
        .ticket { font-size: 13px; color: #6b7280; margin-bottom: 16px; }
        .ticket b { color: #1f2937; }
        .msg { white-space: pre-line; font-size: 15px; line-height: 1.7; margin: 16px 0; }
        .btn { display: inline-block; margin-top: 16px; padding: 12px 24px; background: #4f46e5; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; }
        .foot { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="head">
            <h1>{{ __('Hola, :name', ['name' => $artist->name]) }}</h1>
            <span>QRTE — {{ __('Soporte') }}</span>
        </div>
        <div class="body">
            <div class="ticket">
                <b>{{ $ticket->number }}</b> — {{ $ticket->subject }}<br>
                {{ $ticket->topicLabel() }} · {{ $ticket->created_at->format('d/m/Y H:i') }}
            </div>
            <div class="msg">{!! nl2br(e($replyBody)) !!}</div>
            <a class="btn" href="{{ url('/tickets/'.$ticket->number) }}">{{ __('Ver mi ticket y responder') }}</a>
            <p style="font-size: 12px; color: #6b7280; margin-top: 24px;">
                {{ __('Este correo no admite respuestas. Para continuar la conversación, abre tu ticket de soporte en la plataforma y deja tu mensaje allí.') }}<br>
                <a href="{{ url('/tickets/'.$ticket->number) }}" style="color: #4f46e5;">{{ url('/tickets/'.$ticket->number) }}</a>
            </p>
        </div>
        <div class="foot">
            **QRTE** — {{ __('Identidad digital para obras de arte') }} · POORdesigner.com
        </div>
    </div>
</body>
</html>