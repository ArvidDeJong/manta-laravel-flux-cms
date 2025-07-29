# Blade Components

This package contains various Blade components that you can use in your views. These are automatically registered with the prefix `manta`.

## Usage

```blade
{{-- Use the header component --}}
<x-manta.header-flux />
```

## Troubleshooting

If you have problems loading Blade components, try the following:

1. Clear the view cache: `php artisan view:clear`
2. Check if the ServiceProvider is correctly registered
3. Publish the views: `php artisan vendor:publish --provider="Manta\FluxCMS\FluxCMSServiceProvider" --tag="views"`

## Available Components

The components are located in the `resources/views/components` directory of the package and can be used with the `manta` prefix.

For a complete list of available components, check the `resources/views/components` directory in the package source code.
