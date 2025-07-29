# Configuration

## Configuration File

After publishing the configuration, you can find the configuration file in:

```
config/flux-cms.php
```

## Database Configuration

The package uses the standard Laravel database configuration. Make sure your database connection is correctly configured in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

## Environment Variables

Add the following variables to your `.env` file:

```env
# CMS Configuration
CMS_PREFIX=cms
CMS_MIDDLEWARE=web,auth
CMS_GUARD=web

# FluxUI Configuration
FLUX_UI_ENABLED=true
FLUX_UI_THEME=default

# Livewire Configuration (Laravel 12 + Livewire 3)
LIVEWIRE_ASSET_URL=null
LIVEWIRE_UPDATE_METHOD=POST
```

## Middleware Configuration

The package uses standard Laravel middleware. You can add custom middleware in the configuration:

```php
'middleware' => [
    'web',
    'auth',
    // Add custom middleware here
],
```

## Guards & Authentication

The package works with Laravel's authentication system. Configure your guards in `config/auth.php`:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    
    'staff' => [
        'driver' => 'session',
        'provider' => 'staff',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    
    'staff' => [
        'driver' => 'eloquent',
        'model' => Manta\FluxCMS\Models\MantaStaff::class,
    ],
],
```

## Route Configuration

Routes are automatically loaded with the `cms` prefix. You can customize this in the configuration:

```php
'route_prefix' => 'cms',
'route_middleware' => ['web', 'auth'],
```

## View Configuration

Views are automatically loaded from the package. You can override them by publishing them:

```bash
php artisan vendor:publish --provider="Manta\FluxCMS\FluxCMSServiceProvider" --tag="views"
```

Published views will be located in:

```
resources/views/vendor/flux-cms/
```

## Asset Configuration

For FluxUI and Tailwind CSS, make sure your build process is correctly configured in `vite.config.js`:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
```

And in `tailwind.config.js`:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/livewire/flux-pro/stubs/**/*.blade.php",
        "./vendor/livewire/flux/stubs/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
```

## Cache Configuration

For optimal performance, configure Redis for cache and sessions:

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Logging

The package logs activities to Laravel's standard log system. Configure logging in `config/logging.php`:

```php
'channels' => [
    'cms' => [
        'driver' => 'daily',
        'path' => storage_path('logs/cms.log'),
        'level' => 'debug',
        'days' => 14,
    ],
],
```
