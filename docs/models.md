# Models

This package contains various Eloquent models for managing CMS functionality.

## MantaRoute

The `MantaRoute` model manages Laravel routes in the database for route-based navigation and permissions.

### Database Table

Table: `manta_routes`

| Field | Type | Description |
|-------|------|-------------|
| `id` | bigint | Primary key |
| `uri` | string | Route URI (e.g. 'cms/dashboard') |
| `name` | string | Route name (e.g. 'cms.dashboard') |
| `prefix` | string | Route prefix (e.g. 'cms') |
| `active` | boolean | Whether the route is active (default: 1) |
| `created_by` | bigint | ID of user who created the record |
| `updated_by` | bigint | ID of user who updated the record |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Update timestamp |

### Usage

```php
use Manta\FluxCMS\Models\MantaRoute;

// Retrieve all active routes
$activeRoutes = MantaRoute::active()->get();

// Filter routes by prefix
$cmsRoutes = MantaRoute::byPrefix('cms')->get();

// Specific CMS routes (shortcut)
$cmsRoutes = MantaRoute::cms()->get();

// Find route by name
$dashboardRoute = MantaRoute::where('name', 'cms.dashboard')->first();

// Create new route
$route = MantaRoute::create([
    'uri' => 'cms/settings',
    'name' => 'cms.settings',
    'prefix' => 'cms',
    'active' => true
]);
```

### Query Scopes

#### `scopeActive($query)`
Filters only active routes:

```php
$activeRoutes = MantaRoute::active()->get();
```

#### `scopeByPrefix($query, $prefix)`
Filters routes by prefix:

```php
$adminRoutes = MantaRoute::byPrefix('admin')->get();
```

#### `scopeCms($query)`
Shortcut for CMS routes (prefix = 'cms'):

```php
$cmsRoutes = MantaRoute::cms()->get();
```

### Fillable Fields

```php
protected $fillable = [
    'uri',
    'name', 
    'prefix',
    'active',
    'created_by',
    'updated_by'
];
```

### Casts

```php
protected $casts = [
    'active' => 'boolean'
];
```

### Automatic Fields

The model automatically fills the `created_by` and `updated_by` fields with the ID of the logged-in user when creating and updating records.

### Synchronization

Routes can be synchronized from Laravel's route definition with the `manta:sync-routes` command:

```bash
# Synchronize all routes
php artisan manta:sync-routes

# Only CMS routes
php artisan manta:sync-routes --prefix=cms
```

## Project Structure

All models can be found in the `src/Models` directory. They follow standard Laravel conventions and contain relationships and scopes relevant to the CMS.

The models are enhanced with type hints, query scopes, relationships and PHPDoc annotations for better IDE support and developer experience.

## Available Models

- **User** - Users with extended profile information
- **Audit** - Logging of system events
- **Company** - Company information
- **Contactperson** - Contact persons linked to companies
- **Firewall** - IP blocking and access control
- **Iplist** - Lists of IP addresses for security
- **Mailtrap** - Email catching and debugging
- **Option** - System settings and configurations
- **Routeseo** - SEO information for routes
- **Staff** - Staff information, with authentication support
- **StaffLog** - Logging of staff activities
- **Translation** - Translations for multilingual support
- **Upload** - File uploads
- **UserLog** - Logging of user activities
- **Userregister** - User registration data

## Example Usage

Example of using a model with query scopes:

```php
// Example of using a model
use Manta\FluxCMS\Models\User;

// Get all active admin users
$admins = User::active()->admin()->get();

// Search by name or email
$searchResults = User::search('Johnson')->get();

// Filter by company
$companyUsers = User::byCompany(5)->get();
```

## Model Features

- **Type Hints**: All models include proper type hints for better IDE support
- **Query Scopes**: Convenient methods for common queries
- **Relationships**: Properly defined Eloquent relationships
- **PHPDoc Annotations**: Complete documentation for all properties and methods
- **Automatic Timestamps**: Created and updated timestamps are handled automatically
- **User Tracking**: Many models automatically track which user created/updated records

For detailed documentation of individual models, see the model files in `/src/Models/`.
