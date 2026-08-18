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

];
