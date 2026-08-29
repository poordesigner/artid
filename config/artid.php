<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Claves de firma (HMAC) para los QR, versionadas
    |--------------------------------------------------------------------------
    |
    | Se usan para firmar/verificar el vínculo QR <-> ficha. Cada versión
    | apunta a una clave. Al rotar, se agrega una nueva versión y se mantienen
    | las anteriores para que los QR ya impresos sigan validando.
    |
    */

    'signing_keys' => [
        'v1' => env('ARTID_SIGNING_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Versión activa para firmar QRs nuevos
    |--------------------------------------------------------------------------
    */

    'active_signing_version' => 'v1',

    /*
    |--------------------------------------------------------------------------
    | URL pública base de la plataforma
    |--------------------------------------------------------------------------
    |
    | Se usa para construir las URLs firmadas que codifican los QR.
    |
    */

    'public_url' => env('ARTID_PUBLIC_URL', 'https://poordesigner.com'),

    /*
    |--------------------------------------------------------------------------
    | Tokens de bienvenida (primer registro)
    |--------------------------------------------------------------------------
    |
    | Cantidad de tokens gratis que recibe un artista la primera vez que crea
    | su cuenta. Solo se otorga una vez; se controla con la columna
    | `welcome_tokens_claimed`.
    |
    */

    'welcome_tokens' => (int) env('ARTID_WELCOME_TOKENS', 5),

];
