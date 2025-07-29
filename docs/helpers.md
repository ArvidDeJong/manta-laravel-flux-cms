# Helper Functions

This package contains various helper functions that you can use in your application. These are automatically loaded via Composer's autoload mechanism.

## Available Helpers

```php
// Get the current version of the package
manta_cms_version();

// Generate a URL to an asset in the package
manta_cms_asset('js/app.js');

// Get a value from the manta-cms configuration
manta_cms_config('key', 'default_value');

// Generate a URL to a route in the package
manta_cms_route('dashboard');

// Get the current logged-in user
manta_cms_user();

// Get the current logged-in staff user
manta_cms_staff();

// Other available helpers
cms_config('key');
module_config('module_name', 'key');
generatePassword($length = 12);
getLocaleManta();
getLocalesManta();
getRoutesManta();
word_wrap($text, $length = 75);
translate($key, $locale = null);
```

## Troubleshooting Helpers

If you get an error message like `Call to undefined function manta_cms_config()`, try the following:

1. Run `composer dump-autoload` in your Laravel project
2. If that doesn't work, try `composer update`
3. Check if the ServiceProvider is correctly registered in `config/app.php`
4. Clear the cache with `php artisan cache:clear`

As a last resort, you can manually load the helpers by adding this line to your `bootstrap/app.php`:

```php
require_once __DIR__ . '/../vendor/manta/laravel-manta-cms/src/helpers.php';
```
