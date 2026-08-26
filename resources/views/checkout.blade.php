<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — {{ __('Pago') }}</title>
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9fafb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            max-width: 420px;
            width: 90%;
        }
        .logo {
            width: 64px;
            height: 64px;
            border-radius: 12px;
        }
        h1 { font-size: 1.4rem; font-weight: 600; color: #111827; margin: 16px 0 8px; }
        p  { font-size: 0.95rem; color: #6b7280; margin: 0 0 24px; }
        .spinner {
            width: 32px; height: 32px;
            border: 3px solid #e5e7eb;
            border-top-color: #4f46e5;
            border-radius: 50%;
            margin: 0 auto;
            animation: spin 0.8s linear infinite;
        }
        .spinner-lg {
            width: 40px; height: 40px;
            border: 4px solid #e0e7ff;
            border-top-color: #4f46e5;
            border-radius: 50%;
            margin: 0 auto;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .error { color: #dc2626; font-size: 0.9rem; display: none; }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('img/logo_simple.png') }}" alt="ARTid" class="logo">
        <div id="start">
            <h1>{{ __('Procesando tu pago...') }}</h1>
            <p>{{ __('Te estamos redirigiendo a un pago seguro.') }}</p>
            <div class="spinner" id="spinner"></div>
        </div>
        <div id="done" style="display:none;">
            <h1>✓ {{ __('¡Pago completado!') }}</h1>
            <p>{{ __('Verificando tu suscripción... te redirigimos a tu plan.') }}</p>
            <div class="spinner-lg"></div>
        </div>
        <p class="error" id="error">{{ __('No se pudo iniciar el checkout.') }}</p>
    </div>

    <script>
        const token = '{{ config('paddle.client_token') }}';
        const params = new URLSearchParams(window.location.search);
        const transactionId = params.get('_ptxn');
        let completed = false;

        function redirectAfterSuccess() {
            if (completed) {
                window.location.href = '/configuracion?tab=mi-plan';
            }
        }

        function showError() {
            document.getElementById('spinner').style.display = 'none';
            document.getElementById('error').style.display = 'block';
        }

        function showCompleted() {
            document.getElementById('start').style.display = 'none';
            document.getElementById('done').style.display = 'block';
        }

        if (!token || !transactionId) {
            showError();
        } else {
            Paddle.Environment.set('sandbox');

            Paddle.Initialize({
                token: token,
                eventCallback: function (data) {
                    if (data && data.name === 'checkout.completed') {
                        completed = true;
                        showCompleted();
                        setTimeout(redirectAfterSuccess, 3000);
                    }
                },
            }).then(function () {
                return Paddle.Checkout.open({
                    transactionId: transactionId,
                });
            }).catch(function (err) {
                console.error('Paddle error:', err);
                showError();
            });
        }
    </script>
</body>
</html>