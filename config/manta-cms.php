<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Flux CMS Algemene Instellingen
    |--------------------------------------------------------------------------
    |
    | Hier kun je de algemene instellingen voor de Flux CMS package configureren.
    |
    */

    'name' => 'Flux CMS',

    /*
    |--------------------------------------------------------------------------
    | Route Instellingen
    |--------------------------------------------------------------------------
    |
    | Configureer de prefix, middleware en route-instellingen voor het CMS.
    |
    */

    'routes' => [
        'prefix' => 'cms',
        'middleware' => ['web', 'auth'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Gebruikers Rechten
    |--------------------------------------------------------------------------
    |
    | Configureer welke gebruikers toegang hebben tot het CMS.
    |
    */

    'rights' => [
        'staff' => [
            'label' => 'Medewerkers',
            'rights' => [
                [
                    'key'   => 'staff.list',
                    'label' => 'Lijst',
                ],
                [
                    'key'   => 'staff.developer',
                    'label' => 'Ontwikkelaar',
                ],
                [
                    'key'   => 'staff.admin',
                    'label' => 'Admin',
                ],
            ],
        ],
        'user' => [
            'label' => 'Klanten',
            'rights' => [
                [
                    'key'   => 'user.list',
                    'label' => 'Lijst',
                ],
            ],
        ],
        'usergeneral' => [
            'label' => 'Algemeen',
            'rights' => [
                [
                    'key'   => 'usergeneral.create',
                    'label' => 'Aanmaken',
                ],
                [
                    'key'   => 'usergeneral.edit',
                    'label' => 'Bewerken',
                ],
                [
                    'key'   => 'usergeneral.delete',
                    'label' => 'Verwijderen',
                ],
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Media Instellingen
    |--------------------------------------------------------------------------
    |
    | Configuratie voor het mediabeheer van het CMS.
    |
    */

    'media' => [
        'disk' => 'public',
        'path' => 'cms',
        'allowed_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'video/mp4',
        ],
        'max_file_size' => 10 * 1024, // 10MB in kilobytes
        'thumbnails' => [500, 800, 1080],
    ],

    /*
    |--------------------------------------------------------------------------
    | Faker Instellingen
    |--------------------------------------------------------------------------
    |
    | Configuratie voor het gebruik van faker data in componenten.
    | Handig tijdens ontwikkeling voor het automatisch invullen van formulieren.
    |
    */

    'faker' => [
        'enabled' => env('FAKER_ENABLED', true),
    ],


];
