<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Entorno de Paddle
    |--------------------------------------------------------------------------
    | sandbox | production
    */
    'env' => env('PADDLE_ENV', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | API key (server-side)
    |--------------------------------------------------------------------------
    | Key con prefijo pdl_ que se usa en Authorization: Bearer.
    */
    'api_key' => env('PADDLE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Webhook secret
    |--------------------------------------------------------------------------
    | endpoint_secret_key del notification destination para verificar firmas.
    */
    'webhook_secret' => env('PADDLE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Client-side token (Paddle.js)
    |--------------------------------------------------------------------------
    | Token test_ (sandbox) o live_ (producción) para inicializar Paddle.js
    | en la página de checkout.
    */
    'client_token' => env('PADDLE_CLIENT_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Base URL de la API
    |--------------------------------------------------------------------------
    */
    'base_url' => env('PADDLE_ENV', 'sandbox') === 'production'
        ? 'https://api.paddle.com'
        : 'https://sandbox-api.paddle.com',
];