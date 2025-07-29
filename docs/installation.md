# Installation

Installation instructions for the [Manta](https://manta.website) Laravel Flux CMS package.

## Requirements

- PHP 8.2 or higher
- Laravel 12.x
- Livewire 3.x

## Installation via Composer

You can install this package via Composer:

```bash
composer require manta/laravel-manta-cms
```

## ServiceProvider Registration

After installation, you need to register the ServiceProvider in `config/app.php`:

```php
'providers' => [
    // Other providers...
    Manta\FluxCMS\FluxCMSServiceProvider::class,
],
```

## Publishing Files

Next, you can publish the configuration, views and migrations:

```bash
# Publish only configuration
php artisan vendor:publish --provider="Manta\FluxCMS\FluxCMSServiceProvider" --tag="config"

# Publish only views
php artisan vendor:publish --provider="Manta\FluxCMS\FluxCMSServiceProvider" --tag="views"

# Publish only migrations
php artisan vendor:publish --provider="Manta\FluxCMS\FluxCMSServiceProvider" --tag="migrations"

# Or publish everything at once
php artisan vendor:publish --provider="Manta\FluxCMS\FluxCMSServiceProvider"
```

## Database Setup

Run the migrations to create the required tables:

```bash
php artisan migrate
```

## Clear Cache

After installation, it is recommended to clear the cache:

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

## Verification

You can check if the installation was successful by navigating to the following route:

```
/cms/dashboard
```

Or by running one of the available Artisan commands:

```bash
php artisan list manta
```
