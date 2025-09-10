<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which public pages should be included in the sitemap.xml
    |
    */

    'urls' => [
        [
            'url' => '/',
            'changefreq' => 'daily',
            'priority' => '1.0',
        ],
        [
            'url' => '/about',
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ],
        [
            'url' => '/contact',
            'changefreq' => 'monthly',
            'priority' => '0.7',
        ],
        [
            'url' => '/services',
            'changefreq' => 'weekly',
            'priority' => '0.9',
        ],
        // Add more public URLs here
    ],

    /*
    |--------------------------------------------------------------------------
    | Dynamic Routes
    |--------------------------------------------------------------------------
    |
    | Enable dynamic route discovery from the database
    |
    */
    'dynamic_routes' => [
        'enabled' => true,
        'exclude_prefixes' => ['cms', 'admin', 'staff', 'account', 'filemanager', 'flux', 'livewire'],
        'exclude_patterns' => [
            // Add custom patterns here to exclude specific routes
            // Examples:
            // '*/private/*',
            // '*beta*',
            // '*test*',
        ],
        'default_changefreq' => 'weekly',
        'default_priority' => '0.8',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour in seconds
    ],
];
