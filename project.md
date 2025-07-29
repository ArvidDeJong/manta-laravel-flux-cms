# Project: Manta Laravel Flux CMS

This document contains the basic rules and guidelines for this project. It serves as a reference for both developers and AI assistants.

## Technical Stack

- **PHP version**: ^8.2
- **Laravel version**: 12.x
- **Livewire version**: 3.x
- **Front-end**: FluxUI.dev and/or Tailwind CSS v4
- **Package type**: Library/Package

## Project Structure

- **Namespace**: `Manta\FluxCMS`
- **Livewire components**: Located in `\App\Livewire` (according to Livewire 3 convention)
- **Models**: Located in `src/Models`
- **Controllers**: Located in `src/Http/Controllers`
- **Views**: Located in `resources/views`
- **Assets**: JavaScript libraries are managed in the `/libs` directory
- **Blade components**: Registered with the prefix `manta` (e.g. `<x-manta.header-flux />`)

## Coding Standards

- **Language**: Dutch for user-facing content, English for code and comments
- **PSR standards**: Follow PSR-12 for code style
- **Laravel Best Practices**: Follow the official Laravel conventions
- **Livewire Conventions**: Follow the official Livewire 3 conventions
- **CSS**: Use FluxUI components and/or Tailwind CSS classes
- **Lint errors**: Ignore lint errors that are specifically related to Laravel or Livewire conventions that deviate from standard PHP conventions

## Important Documentation URLs

- **FluxUI documentation**: https://fluxui.dev/
- **Tailwind CSS**: https://tailwindcss.com/
- **Laravel documentation**: https://laravel.com/docs/12.x
- **Livewire documentation**: https://livewire.laravel.com/

## Component References

### FluxUI Components

- Layout: https://fluxui.dev/layouts/header
- Button: https://fluxui.dev/components/button
- Table: https://fluxui.dev/components/table
- Checkbox: https://fluxui.dev/components/checkbox
- Input: https://fluxui.dev/components/input
- Select: https://fluxui.dev/components/select
- Date picker: https://fluxui.dev/components/date-picker
- Icon: https://fluxui.dev/components/icon (icons via https://heroicons.com/)
- Badge: https://fluxui.dev/components/badge
- Tabs: https://fluxui.dev/components/tabs
- Modal: https://fluxui.dev/components/modal
- Radio: https://fluxui.dev/components/radio
- Dropdown: https://fluxui.dev/components/dropdown
- Heading: https://fluxui.dev/components/heading
- Editor: https://fluxui.dev/components/editor
- Chart: https://fluxui.dev/components/chart
- Brand: https://fluxui.dev/components/brand
- Avatar: https://fluxui.dev/components/avatar
- Autocomplete: https://fluxui.dev/components/autocomplete
- Callout/Alert: https://fluxui.dev/components/callout

## Helper Functions

The package contains various helper functions:

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

## Creating and Registering New Livewire Components

Follow these steps to create and register a new Livewire component in the package:

1. **Create the Livewire Component Class:**
   - Create a new PHP class in the appropriate directory (`src/Livewire/{ModuleName}/`) that extends `Livewire\Component`
   - Use the `#[Layout('manta-cms::layouts.app')]` attribute for consistent layout
   - Implement required methods like `mount()`, `render()`, and any action methods (e.g., `save()`)
   - Use appropriate traits like `MantaTrait` or module-specific traits

2. **Create the Blade View:**
   - Create a blade view file in `resources/views/livewire/{module-name}/` or use an existing generic template
   - Follow the FluxUI component conventions
   - For translations, use the `{{ __('manta-cms::messages.key_name') }}` syntax

3. **Register the Component in FluxCMSServiceProvider:**
   - Add the component to the `boot()` method:
   ```php
   Livewire::component('manta-cms::{module}.{action}', \Manta\FluxCMS\Livewire\{Module}\{Module}{Action}::class);
   ```

4. **Add Routes in web.php:**
   - Define routes within the appropriate middleware group:
   ```php
   Route::get('/{module}/{action}', \Manta\FluxCMS\Livewire\{Module}\{Module}{Action}::class)->name('{module}.{action}');
   ```
   - For components with parameters, use route model binding:
   ```php
   Route::get('/{module}/{parameter}/{action}', \Manta\FluxCMS\Livewire\{Module}\{Module}{Action}::class)->name('{module}.{action}');
   ```

5. **Add Required Translations:**
   - Add new translation keys to `resources/lang/en/messages.php` and `resources/lang/nl/messages.php`
   - Use the namespace prefix `manta-cms::` when referencing translations in views

## Important Notes

- Make sure `<flux:form>` is not used, this does not exist
- When developing this package, regularly clear the cache: `php artisan view:clear`, `php artisan cache:clear`, `php artisan config:clear`
- After changes to helpers.php run `composer dump-autoload`
- For local development you can create a symbolic link with `composer link`
