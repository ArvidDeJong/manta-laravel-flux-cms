# Installation

Installation instructions for the [Manta](https://manta.website) Laravel Flux CMS package.

## Requirements

- PHP 8.2 or higher
- Laravel 12.x
- Livewire 3.x

## Quick Installation (Recommended)

The fastest way to get started is using the automated installation command:

```bash
# Install the package
composer require manta/laravel-manta-cms

# Run the complete installation
php artisan manta:install --with-migrations
```

**What this command does:**
- Registers the ServiceProvider automatically
- Publishes configuration files
- Publishes views and public assets
- Publishes and runs database migrations
- **Creates a default company** (if no companies exist)
- Synchronizes Laravel routes to database
- Clears all caches
- Runs composer dump-autoload

## Manual Installation

If you prefer manual control over each step:

### Step 1: Install via Composer

```bash
composer require manta/laravel-manta-cms
```

### Step 2: ServiceProvider Registration

Register the ServiceProvider in `config/app.php`:

```php
'providers' => [
    // Other providers...
    Manta\FluxCMS\FluxCMSServiceProvider::class,
],
```

### Step 3: Publishing Files

Publish the configuration, views and migrations:

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

### Step 4: Database Setup

Run the migrations to create the required tables:

```bash
php artisan migrate
```

### Step 5: Seed Default Company

Create a default company if your database is empty:

```bash
php artisan manta:seed-company
```

This command will:
- Check if companies already exist in the database
- Create a default company with basic information if none exist
- Skip creation if companies are already present

**Default company details:**
- Name: "Default Company"
- Number: "COMP-001"
- Address: "Default Street 1, 1000 AA Amsterdam"
- Country: Netherlands
- Status: Active

### Step 6: Clear Cache

After installation, clear the cache:

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
