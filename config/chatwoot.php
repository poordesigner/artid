<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Chatwoot (soporte) - widget de chat
    |--------------------------------------------------------------------------
    |
    | El widget se renderiza en el dashboard del artista y en la landing
    | (Alcance 1). Se desactiva automáticamente si website_token está vacío.
    |
    */

    'base_url' => env('CHATWOOT_BASE_URL', 'https://cwoot.poordesigner.com'),

    'website_token' => env('CHATWOOT_WEBSITE_TOKEN', '1CK1mRPWHH6Zw4dzVHXW87bL'),

];