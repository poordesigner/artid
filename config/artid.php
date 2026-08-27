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
    | Logos en R2
    |--------------------------------------------------------------------------
    | Se sirven desde R2 (no del repo) para poder cambiar el branding sin
    | tocar el deploy. Cambiar la versión en 'logos/v2/...' al iterar.
    */

    'logos' => [
        'navbar' => 'https://pub-10efd14d011c4a98a3d5281d393c13d1.r2.dev/logos/v2/navbar_240x120.png',
        'main' => 'https://pub-10efd14d011c4a98a3d5281d393c13d1.r2.dev/logos/v2/logo_600x300.png',
        'box' => 'https://pub-10efd14d011c4a98a3d5281d393c13d1.r2.dev/logos/v2/logo_box_1024x1024.png',
        'favicon' => 'https://pub-10efd14d011c4a98a3d5281d393c13d1.r2.dev/logos/favicon.png',
        'apple_touch' => 'https://pub-10efd14d011c4a98a3d5281d393c13d1.r2.dev/logos/apple-touch-icon.png',
    ],

];
