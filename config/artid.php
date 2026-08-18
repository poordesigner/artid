<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Clave de firma (HMAC) para los QR
    |--------------------------------------------------------------------------
    |
    | Se usa para firmar/verificar el vínculo QR <-> ficha. Se mantiene en las
    | variables de entorno y nunca debe versionarse en el repositorio.
    |
    */

    'signing_key' => env('ARTID_SIGNING_KEY'),

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
