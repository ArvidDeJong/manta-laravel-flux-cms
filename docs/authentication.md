# Authentication

The Manta Laravel Flux CMS package provides a comprehensive dual authentication system designed to separate CMS administration from regular user accounts.

## Overview

The package implements two distinct authentication systems:

1. **Staff Authentication** (`auth:staff`) - For CMS administrators, editors, and staff members
2. **Account Authentication** (`auth`) - For regular website users and customers

## Authentication Guards

### Staff Guard

The staff guard is specifically designed for CMS access and uses the `Staff` model:

```php
'guards' => [
    'staff' => [
        'driver' => 'session',
        'provider' => 'staff',
    ],
],

'providers' => [
    'staff' => [
        'driver' => 'eloquent',
        'model' => Manta\FluxCMS\Models\Staff::class,
    ],
],
```

### Default Web Guard

The default web guard is used for regular users and uses the standard `User` model:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],
```

## Route Protection

### CMS Routes (Staff Authentication)

All CMS routes are protected with the `auth:staff` middleware:

```php
Route::middleware(['web', 'auth:staff'])
    ->prefix(config('manta-cms.routes.prefix'))
    ->name('manta-cms.')
    ->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');
        Route::get('/medewerkers', StaffList::class)->name('staff.list');
        // ... other CMS routes
    });
```

### Public Routes (No Authentication)

Authentication routes themselves are public and don't require authentication:

```php
Route::middleware('web')
    ->name('flux-cms.')
    ->group(function () {
        // Staff authentication routes
        Route::get('/staff/login', LoginForm::class)->name('staff.login');
        Route::get('/staff/forgot-password', ForgotPassword::class)->name('staff.forgot-password');
        
        // Account authentication routes  
        Route::get('/account/login', LoginForm::class)->name('account.login');
        Route::get('/account/forgot-password', ForgotPassword::class)->name('account.forgot-password');
    });
```

## Authentication Redirects

### Automatic Configuration

The `php artisan manta:install` command automatically configures authentication redirects in your `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo(function ($request) {
        // CMS routes redirect to staff login
        if ($request->is('cms/*') || $request->is('medewerkers/*') || $request->is('bedrijven/*')) {
            return route('flux-cms.staff.login');
        }
        
        // All other routes redirect to account login
        return route('flux-cms.account.login');
    });
})
```

### Route Patterns

The redirect logic uses the following patterns:

- **CMS Routes:** `cms/*`, `medewerkers/*`, `bedrijven/*` → Staff Login
- **All Other Routes:** → Account Login

## Available Routes

### Staff Authentication Routes

| Route | URL | Purpose |
|-------|-----|---------|
| `flux-cms.staff.login` | `/staff/login` | Staff login form |
| `flux-cms.staff.forgot-password` | `/staff/forgot-password` | Staff password reset request |
| `flux-cms.staff.reset-password` | `/staff/reset-password/{token?}` | Staff password reset form |
| `flux-cms.staff.verify-email` | `/staff/verify-email` | Staff email verification |
| `flux-cms.staff.logout` | `/staff/logout` | Staff logout |

### Account Authentication Routes

| Route | URL | Purpose |
|-------|-----|---------|
| `flux-cms.account.login` | `/account/login` | User login form |
| `flux-cms.account.forgot-password` | `/account/forgot-password` | User password reset request |
| `flux-cms.account.reset-password` | `/account/reset-password/{token?}` | User password reset form |
| `flux-cms.account.verify-email` | `/account/verify-email` | User email verification |
| `flux-cms.account.logout` | `/account/logout` | User logout |

### Special Routes

| Route | URL | Purpose |
|-------|-----|---------|
| `flux-cms.login` | `/login` | Alias that redirects to staff login |

## Staff Management

### Creating Staff Users

Use the built-in command to create staff users:

```bash
# Interactive creation
php artisan manta:create-staff

# Direct creation with parameters
php artisan manta:create-staff --email=admin@example.com --password=secure-password
```

### Staff User Properties

The `Staff` model includes the following key properties:

- `name` - Full name
- `email` - Email address (unique)
- `password` - Hashed password
- `active` - Boolean status (active/inactive)
- `email_verified_at` - Email verification timestamp
- `remember_token` - Remember me token

### Staff Permissions

The package includes a basic permission system for staff users. Permissions can be managed through the CMS interface at `/cms/medewerkers/{staff}/rights`.

## Login Form Features

### Proactive Error Handling

The staff login form includes proactive checks to prevent common issues:

1. **Staff User Existence Check** - Verifies that staff users exist in the database
2. **Active User Check** - Ensures there are active staff users available
3. **Clear Error Messages** - Provides helpful instructions when issues are detected

### Error Messages

- **No Staff Users:** "Er zijn nog geen staff gebruikers aangemaakt. Voer 'php artisan manta:create-staff' uit om een staff gebruiker aan te maken."
- **No Active Staff Users:** "Er zijn geen actieve staff gebruikers. Controleer de database of voer 'php artisan manta:create-staff' uit."

## Troubleshooting

### Route Not Found Errors

If you encounter `Route [login] not defined` errors:

1. Ensure authentication redirects are configured in `bootstrap/app.php`
2. Run `php artisan manta:install --force` to reconfigure
3. Clear route cache: `php artisan route:clear`

### Authentication Issues

If staff users cannot log in:

1. Check if staff users exist: `php artisan tinker` → `Manta\FluxCMS\Models\Staff::count()`
2. Verify staff users are active: `Manta\FluxCMS\Models\Staff::where('active', true)->count()`
3. Create a new staff user: `php artisan manta:create-staff`

### Middleware Issues

If authentication redirects are not working:

1. Verify `bootstrap/app.php` contains the redirect configuration
2. Check that routes are properly cached: `php artisan route:list`
3. Clear all caches: `php artisan optimize:clear`

## Security Considerations

### Password Requirements

Both staff and account authentication use Laravel's default password validation rules:

- Minimum 8 characters
- Confirmation required during registration/reset

### Session Security

- Sessions are encrypted using Laravel's default encryption
- Remember tokens are automatically generated and rotated
- CSRF protection is enabled on all authentication forms

### Email Verification

Email verification is supported for both authentication systems and can be enabled via configuration.

## Configuration

### CMS Configuration

The main CMS configuration is located in `config/manta-cms.php`:

```php
return [
    'routes' => [
        'prefix' => env('MANTA_CMS_PREFIX', 'cms'),
        'middleware' => ['web', 'auth:staff'],
    ],
    
    'user_register' => env('MANTA_USER_REGISTER', false),
    'user_verify' => env('MANTA_USER_VERIFY', false),
    'user_home' => env('MANTA_USER_HOME', '/'),
];
```

### Environment Variables

Add these to your `.env` file to customize authentication behavior:

```env
# CMS Configuration
MANTA_CMS_PREFIX=cms

# User Registration & Verification
MANTA_USER_REGISTER=false
MANTA_USER_VERIFY=false
MANTA_USER_HOME=/dashboard
```
