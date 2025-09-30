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
- **Configures authentication redirects** in `bootstrap/app.php`
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

## Authentication Configuration

The Manta CMS package uses two separate authentication systems:

1. **Staff Authentication** - For CMS administrators and staff members
2. **Account Authentication** - For regular website users

### Automatic Configuration

When you run `php artisan manta:install`, the authentication redirects are automatically configured in your `bootstrap/app.php` file. This ensures that:

- CMS routes (`/cms/*`, `/medewerkers/*`, `/bedrijven/*`) redirect unauthenticated users to the **staff login** page
- All other routes redirect unauthenticated users to the **account login** page

### Manual Configuration

If you need to configure authentication redirects manually, add this to your `bootstrap/app.php` in the `withMiddleware` section:

```php
->withMiddleware(function (Middleware $middleware) {
    // Configure authentication redirects for different guards
    $middleware->redirectGuestsTo(function ($request) {
        // Check if the request is for staff routes (CMS routes)
        if ($request->is('cms/*') || $request->is('medewerkers/*') || $request->is('bedrijven/*')) {
            return route('flux-cms.staff.login');
        }
        
        // Default redirect for regular users
        return route('flux-cms.account.login');
    });
})
```

### Available Authentication Routes

#### Staff Authentication Routes:
- **Login:** `/staff/login` → `flux-cms.staff.login`
- **Forgot Password:** `/staff/forgot-password` → `flux-cms.staff.forgot-password`
- **Reset Password:** `/staff/reset-password/{token?}` → `flux-cms.staff.reset-password`
- **Logout:** `/staff/logout` → `flux-cms.staff.logout`

#### Account Authentication Routes:
- **Login:** `/account/login` → `flux-cms.account.login`
- **Forgot Password:** `/account/forgot-password` → `flux-cms.account.forgot-password`
- **Reset Password:** `/account/reset-password/{token?}` → `flux-cms.account.reset-password`
- **Logout:** `/account/logout` → `flux-cms.account.logout`

### Creating Staff Users

To create your first staff user for CMS access:

```bash
php artisan manta:create-staff --email=admin@example.com --password=secure-password
```

Or run it interactively:

```bash
php artisan manta:create-staff
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
