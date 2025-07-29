<?php

/**
 * FluxCMS Helper Functions
 *
 * This file contains helper functions for the FluxCMS package.
 * These functions are available throughout the entire application.
 *
 * @package     Manta\FluxCMS
 * @author      Arvid de Jong
 * @copyright   Copyright (c) 2025
 */

use Illuminate\Support\Facades\File;

if (!function_exists('manta_cms_version')) {
    /**
     * Returns the current version of FluxCMS.
     * Dynamically retrieves the version from composer.json
     *
     * @return string
     */
    function manta_cms_version()
    {
        $composerFile = __DIR__ . '/../composer.json';

        if (file_exists($composerFile)) {
            $composerData = json_decode(file_get_contents($composerFile), true);
            if (isset($composerData['version'])) {
                return $composerData['version'];
            }
        }

        // Fallback to package name and version from Composer's installed.json
        // This works when the package is installed via Composer
        $packageName = 'darvis/manta-laravel-flux-cms';
        $installedFile = dirname(__DIR__, 4) . '/vendor/composer/installed.json';

        if (file_exists($installedFile)) {
            $installedData = json_decode(file_get_contents($installedFile), true);

            // Composer v2 format
            if (isset($installedData['packages'])) {
                foreach ($installedData['packages'] as $package) {
                    if ($package['name'] === $packageName && isset($package['version'])) {
                        return $package['version'];
                    }
                }
            }
            // Composer v1 format
            else {
                foreach ($installedData as $package) {
                    if ($package['name'] === $packageName && isset($package['version'])) {
                        return $package['version'];
                    }
                }
            }
        }

        // Fallback if we can't find the version
        return 'onbekend';
    }
}

if (!function_exists('manta_cms_asset')) {
    /**
     * Generates a URL for an asset within the FluxCMS package.
     *
     * @param string $path
     * @return string
     */
    function manta_cms_asset($path)
    {
        return asset('vendor/manta-cms/' . ltrim($path, '/'));
    }
}

if (!function_exists('manta_cms_lib')) {
    /**
     * Generates a URL for a library asset within the FluxCMS package.
     *
     * @param string $library Library name (e.g., 'jquery', 'fontawesome-free-6.7.2-web')
     * @param string $file File path within the library (e.g., 'jquery.min.js', 'css/all.min.css')
     * @return string
     */
    function manta_cms_lib($library, $file = '')
    {

        $path = 'libs/' . $library;
        if ($file) {
            $path .= '/' . ltrim($file, '/');
        }
        return manta_cms_asset($path);
    }
}

if (!function_exists('manta_cms_css')) {
    /**
     * Generates a URL for a CSS file within the FluxCMS package.
     *
     * @param string $path Path to CSS file
     * @return string
     */
    function manta_cms_css($path)
    {
        return manta_cms_asset('css/' . ltrim($path, '/'));
    }
}

if (!function_exists('manta_cms_js')) {
    /**
     * Generates a URL for a JavaScript file within the FluxCMS package.
     *
     * @param string $path Path to JS file
     * @return string
     */
    function manta_cms_js($path)
    {
        return manta_cms_asset('js/' . ltrim($path, '/'));
    }
}

if (!function_exists('manta_cms_img')) {
    /**
     * Generates a URL for an image file within the FluxCMS package.
     *
     * @param string $path Path to image file
     * @return string
     */
    function manta_cms_img($path)
    {
        return manta_cms_asset('images/' . ltrim($path, '/'));
    }
}

if (!function_exists('manta_cms_config')) {
    /**
     * Retrieves a configuration value from the manta-cms config.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function manta_cms_config($key, $default = null)
    {
        return config('manta-cms.' . $key, $default);
    }
}

if (!function_exists('manta_cms_route')) {
    /**
     * Generates a route URL for a FluxCMS route.
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    function manta_cms_route($name, $parameters = [], $absolute = true)
    {
        return route('manta-cms.' . $name, $parameters, $absolute);
    }
}

if (!function_exists('manta_cms_user')) {
    /**
     * Gets the current authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function manta_cms_user()
    {
        return auth()->user();
    }
}

if (!function_exists('manta_cms_staff')) {
    /**
     * Gets the current authenticated staff member using the staff guard.
     *
     * @return \Manta\FluxCMS\Models\Staff|null
     */
    function manta_cms_staff()
    {
        return auth()->guard('staff')->user();
    }
}



if (!function_exists('cms_config')) {
    /**
     * Load a Manta CMS configuration file
     *
     * Attempts to load a theme-specific configuration file first,
     * then falls back to the default configuration file.
     *
     * @param string $name Configuration file name (without extension)
     * @return array The configuration array
     */
    function cms_config($name = 'manta')
    {
        $path = app_path("../manta/config/{$name}.php");
        $pathTheme = app_path("../manta/config/{$name}_" . env('THEME') . ".php");

        if (File::exists($pathTheme)) {
            return include($pathTheme);
        } elseif (!File::exists($path)) {
            $path = app_path("../manta/config/{$name}_default.php");
            // throw new \Exception("Configuration file not found: $path");
        }

        return include($path);
    }
}

if (!function_exists('module_config')) {
    /**
     * Load a module-specific configuration file
     *
     * Attempts to load a theme-specific module configuration file first,
     * then tries the default module configuration, and finally falls back to
     * the default configuration file.
     *
     * @param string $name Module name
     * @return array The module configuration array
     */
    function module_config($name)
    {

        // Check voor een aantal standaardlocaties waar module config kan zijn
        $paths = [
            // Package configuratie pad
            __DIR__ . '/../config/manta-module-' . $name . '.php',
            // Laravel config_path (als beschikbaar)
            function_exists('config_path') ? config_path('manta-module-' . $name . '.php') : null,
            // Relatief pad vanuit de applicatieroot (fallback)
            'config/manta-module-' . $name . '.php'
        ];

        // Service container pad (als beschikbaar)
        if (function_exists('app') && app()->bound('manta.module.config')) {
            // Als de service geregistreerd is, gebruik deze
            try {
                return app('manta.module.config')->get($name);
            } catch (\Throwable $e) {
                // Fallback naar bestandssysteem bij fouten
            }
        }

        // Loop door mogelijke paden
        foreach ($paths as $path) {
            if ($path && file_exists($path)) {
                return include($path);
            }
        }

        // Niets gevonden
        return [];
    }
}

if (!function_exists('generatePassword')) {
    /**
     * Generate a secure random password
     *
     * Creates a password with the specified length that includes at least one number,
     * one lowercase letter, one uppercase letter, and one special character.
     * The remaining characters are randomly selected from the enabled character types.
     *
     * @param int $length Length of the password to generate
     * @param bool $includeNumbers Whether to include numbers in the password
     * @param bool $includeLetters Whether to include letters in the password
     * @param bool $includeSpecialChars Whether to include special characters in the password
     * @return string The generated password or an error message
     */
    function generatePassword($length = 12, $includeNumbers = true, $includeLetters = true, $includeSpecialChars = true)
    {
        $numbers = '0123456789';
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $specialChars = '!@#$%^&*()_-=+;:,.?';

        if (!$includeLetters || !$includeNumbers || !$includeSpecialChars) {
            return 'To meet minimum requirements, letters, numbers, and special characters must be enabled.';
        }

        $characters = '';
        if ($includeNumbers) {
            $characters .= $numbers;
        }
        if ($includeLetters) {
            $characters .= $letters;
        }
        if ($includeSpecialChars) {
            $characters .= $specialChars;
        }

        if ($characters === '') {
            return 'Please enable at least one character type.';
        }

        // Ensure we have at least one of each required character type
        $password = '';
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $specialChars[random_int(0, strlen($specialChars) - 1)];

        // Fill the rest of the password with random characters
        $charactersLength = strlen($characters);
        for ($i = 4; $i < $length; $i++) {
            $password .= $characters[random_int(0, $charactersLength - 1)];
        }

        // Shuffle to avoid predictable pattern
        $passwordArray = str_split($password);
        shuffle($passwordArray);

        return implode('', $passwordArray);
    }
}


if (!function_exists('getLocaleManta')) {
    /**
     * Get the current application locale
     *
     * @return string The current locale code
     */
    function getLocaleManta()
    {
        return env('APP_LOCALE');
    }
}

if (!function_exists('getLocalesManta')) {
    /**
     * Get all supported locales with their display information
     *
     * Returns an array of supported locales with locale code, CSS class for flag icons,
     * and localized display name. Only includes locales that are defined in the
     * SUPPORTED_LOCALES environment variable.
     *
     * @return array Array of supported locales with their metadata
     */
    function getLocalesManta()
    {
        $arr = [
            ['locale' => 'nl', 'class' => 'fi-nl', 'title' => 'Nederlands'],
            ['locale' => 'en', 'class' => 'fi-en', 'title' => 'Engels'],
            ['locale' => 'de', 'class' => 'fi-de', 'title' => 'Duits'],
            ['locale' => 'sv', 'class' => 'fi-sv', 'title' => 'Zweeds'],
            ['locale' => 'es', 'class' => 'fi-es', 'title' => 'Spaans'],
            ['locale' => 'fr', 'class' => 'fi-fr', 'title' => 'Frans'],
        ];

        $supported = explode(',', env('SUPPORTED_LOCALES'));

        return collect($arr)
            ->filter(fn($row) => in_array($row['locale'], $supported))
            ->values()
            ->all();
    }
}


if (!function_exists('getRoutesManta')) {
    /**
     * Get all website routes without locale prefix
     *
     * Retrieves all routes that start with 'website.' and removes the locale prefix
     * if present. Returns a unique, sorted array of route names.
     *
     * @return array Associative array of route names
     */
    function getRoutesManta()
    {
        // Get the desired language from the environment
        $appLocale = env('APP_LOCALE', 'nl');

        return collect(Illuminate\Support\Facades\Route::getRoutes())
            ->pluck('action.as')
            ->filter()
            ->map(function ($routeName) use ($appLocale) {
                // Check if the route starts with the language prefix
                if (preg_match('/^' . $appLocale . '\.(.+)$/', $routeName, $matches)) {
                    return $matches[1]; // Use the route without language prefix
                }
                // If there's no language prefix, keep the original name
                return $routeName;
            })
            ->filter(function ($routeName) {
                // Check if the route starts with 'website.'
                return Illuminate\Support\Str::startsWith($routeName, 'website.');
            })
            ->unique()
            ->sort()
            ->values()
            ->mapWithKeys(function ($routeName) {
                // Use translation or fall back to the route name
                return [$routeName => $routeName];
            })
            ->toArray();
    }
}
if (!function_exists('word_wrap')) {
    /**
     * Wrap a string by word count
     *
     * Splits a string into chunks with a maximum number of words per line,
     * separated by newlines.
     *
     * @param string $string The input string to wrap
     * @param int $maxWords Maximum number of words per line
     * @return string The wrapped string
     */
    function word_wrap($string, $maxWords = 10)
    {
        $words = explode(' ', $string);
        $chunks = array_chunk($words, $maxWords);
        return implode("\n", array_map(fn($chunk) => implode(' ', $chunk), $chunks));
    }
}


if (!function_exists('translate')) {
    /**
     * Get a translated version of a model
     *
     * Retrieves the translated version of a model for the specified locale.
     * If no translation is found, returns the original model.
     *
     * @param object $item The model to translate
     * @param string|null $locale The target locale (defaults to current app locale)
     * @return array Array containing the original model and the translated result
     */
    function translate(object $item, ?string $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();

        // Determine if the original item or a translation was provided
        if (env('APP_LOCALE') == $item->locale || $item->pid == null || $item->locale == null) {
            // Original item
            $org = $item;

            // Try to get translation from original
            $translatedItem = get_class($item)::where([
                'id' => $org->id,
                'locale' => $locale
            ])->first();
        } else {
            // Translation, so get the original
            $org = get_class($item)::find($item->pid);

            // Try to get translation from original
            $find = get_class($item)::where([
                'pid' => $org->id,
                'locale' => $locale
            ])->first();

            if ($find) {
                $translatedItem = $find;
            } else {
                $translatedItem = $org;
            }
        }

        return [
            'org' => $org,
            'result' => $translatedItem ?: $org,
        ];
    }
}
