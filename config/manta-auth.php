<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Configuratie voor de Manta Flux CMS authenticatie.
    |
    */

    'guards' => [
        'staff' => [
            'driver' => 'session',
            'provider' => 'staff',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Providers voor gebruikersauthenticatie.
    |
    */

    'providers' => [
        'staff' => [
            'driver' => 'eloquent',
            'model' => Manta\FluxCMS\Models\Staff::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset Timeout
    |--------------------------------------------------------------------------
    |
    | De tijd in minuten waarin een wachtwoordreset geldig blijft. Standaard
    | is dit 60 minuten.
    |
    */

    'password_timeout' => 60,
];
