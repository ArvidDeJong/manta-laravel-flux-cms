# Artisan Commands

This package contains various handy Artisan commands that you can use for management and maintenance.

## 🎯 Key Commands

```bash
# Complete installation with migrations and seeding
php artisan manta:install --with-migrations

# Seed default company (if none exist)
php artisan manta:seed-company

# Sync routes
php artisan manta:sync-routes --prefix=cms

# Import module settings from packages
php artisan manta:import-module-settings darvis/manta-contact

# Import upload module settings (required for upload functionality)
php artisan manta:import-module-settings darvis/manta-laravel-flux-cms --settings-file=export/settings-upload.php

# Create test user (local)
php artisan manta:create-user --email=test@example.com

# Create staff user (local)
php artisan manta:create-staff --email=admin@example.com

# Refresh package
php artisan manta:refresh
```

## Commands Overview

All commands use the `manta:` prefix for consistency:

```bash
php artisan list manta
```

## manta:sync-routes

Synchronizes Laravel routes to the `MantaRoute` table. This is useful for route-based navigation and permissions.

### Usage

```bash
# Synchronize all routes
php artisan manta:sync-routes

# Only synchronize routes with a specific prefix (e.g. 'cms')
php artisan manta:sync-routes --prefix=cms

# Remove existing routes first and then synchronize
php artisan manta:sync-routes --clear

# Combination: only CMS routes with clear
php artisan manta:sync-routes --prefix=cms --clear
```

### Options

- `--prefix=PREFIX` : Filter routes by prefix
- `--clear` : Remove existing routes before synchronization

### Functionality

The command:
- Retrieves all Laravel routes via `Route::getRoutes()`
- Extracts URI, name and prefix from each route
- Stores these in the `manta_routes` table
- Updates existing routes or creates new ones
- Shows an overview of synchronized routes

**Note:** First run the migration to create the `manta_routes` table:

```bash
php artisan migrate
```

## manta:import-module-settings

Imports module settings from any package into the `MantaModule` model. This command allows you to import configuration and field definitions from different packages into the CMS system.

### Usage

```bash
# Import settings from a package (default: export/settings.php)
php artisan manta:import-module-settings darvis/manta-contact

# Import with custom settings file path
php artisan manta:import-module-settings darvis/manta-contact --settings-file=config/module-settings.php

# Import all settings files from export directory
php artisan manta:import-module-settings darvis/manta-vacancy --all

# Force overwrite existing modules
php artisan manta:import-module-settings darvis/manta-contact --force

# Combine options: import all files and force overwrite
php artisan manta:import-module-settings darvis/manta-vacancy --all --force
```

### Arguments

- `package` : The package name (e.g., `darvis/manta-contact`)

### Options

- `--settings-file=PATH` : Relative path to settings file within package (default: `export/settings.php`)
- `--all` : Import all settings files from the export directory (pattern: `settings*.php`)
- `--force` : Overwrite existing module settings

### Functionality

The command:
- Locates the specified package in the `vendor/` directory
- Loads the settings array from the specified file
- Maps settings directly to `MantaModule` fields (1:1 mapping)
- Validates required fields (`name` is mandatory) and checks for existing modules
- Creates or updates the module in the database
- Displays a summary of imported data

All settings field names should match exactly with the `MantaModule` database columns for seamless import.

### Settings File Format

The settings file should return an array where field names match exactly with `MantaModule` database columns:

```php
<?php
return [
    // Required fields
    "name" => "contact",                    // Unique module identifier
    "title" => "Contact",                  // Display title
    
    // Module name variations (optional)
    "module_name" => [
        "single" => "Contact",              // Singular form
        "multiple" => "Contacts"            // Plural form
    ],
    
    // Optional fields (with common defaults)
    "description" => "Contact module",      // Module description
    "tabtitle" => "firstname",             // Tab title field reference
    "type" => "modules",                   // Module type
    "active" => true,                      // Active status
    "locale" => "nl",                      // Locale
    "sort" => 999,                         // Sort order
    
    // Navigation fields
    "route" => null,                       // Route name
    "url" => null,                         // Direct URL
    "icon" => null,                        // Icon class/name
    
    // Advanced fields
    "rights" => null,                      // Access rights
    "data" => [],                          // Additional data
    
    // Module configuration (stored as JSON)
    "ereg" => [
        "tables" => [
            // Database table definitions
        ]
    ],
    "settings" => [
        // Module-specific settings
    ],
    "fields" => [
        // Field definitions for forms
    ]
];
```

**Available Fields:**
All fields from the `MantaModule` model are supported: `name`, `title`, `module_name`, `description`, `tabtitle`, `type`, `active`, `locale`, `sort`, `route`, `url`, `icon`, `rights`, `data`, `ereg`, `settings`, `fields`, `created_by`, `updated_by`, `deleted_by`, `company_id`, `host`.

## manta:create-staff

Creates a staff user for testing purposes. Only works in the local environment (`APP_ENV=local`).

### Usage

```bash
# With default email (staff@example.com) and generated password
php artisan manta:create-staff

# With custom email
php artisan manta:create-staff --email=admin@yourdomain.com

# With custom email and password
php artisan manta:create-staff --email=admin@yourdomain.com --password=YourPassword
```

### Options

- `--email=EMAIL` : Email address for the staff user
- `--password=PASSWORD` : Password (optional, otherwise a password is generated)

## manta:create-user

Creates a regular user for testing purposes. Only works in the local environment (`APP_ENV=local`).

### Usage

```bash
# With automatically generated name, email and password
php artisan manta:create-user

# With custom name and email
php artisan manta:create-user --name="John Test" --email=user@example.com

# With custom name, email and password
php artisan manta:create-user --name="John Test" --email=user@example.com --password=SecurePassword123
```

### Options

- `--name=NAME` : Name for the user
- `--email=EMAIL` : Email address for the user
- `--password=PASSWORD` : Password (optional, otherwise a password is generated)

## manta:copy-libraries

Copies JavaScript libraries from the `/libs` directory to the `public/js/libs` directory.

### Usage

```bash
# Copy libraries
php artisan manta:copy-libraries

# Overwrite files
php artisan manta:copy-libraries --force
```

### Options

- `--force` : Overwrite existing files in the public directory

### Functionality

- Checks if the `/libs` directory exists, if not, creates it
- Checks if the `public/js/libs` directory exists, if not, creates it
- Copies all files and directories from `/libs` to `public/js/libs`
- Shows an overview of copied and skipped files

## manta:install

Installs and configures the package for first use. This is the recommended way to set up the CMS as it handles all necessary steps automatically.

### Usage

```bash
# Complete installation (recommended)
php artisan manta:install --with-migrations

# Basic installation without migrations
php artisan manta:install

# Force overwrite existing files
php artisan manta:install --with-migrations --force
```

### Options

- `--force` : Overwrite existing files
- `--with-migrations` : Also publish and run migrations
- `--skip-provider` : Skip registering the ServiceProvider

### What it does

1. **Registers ServiceProvider** (unless `--skip-provider` is used)
2. **Publishes configuration files**
3. **Publishes views and public assets**
4. **Publishes and runs migrations** (if `--with-migrations` is used)
5. **Seeds default company** (if no companies exist)
6. **Synchronizes routes** to database
7. **Clears all caches**
8. **Runs composer dump-autoload**

## manta:seed-company

Creates a default company if no companies exist in the database. This is automatically called during `manta:install` but can also be run manually.

### Usage

```bash
# Seed default company
php artisan manta:seed-company
```

### Functionality

The command:
- Checks if any companies exist in the database
- If companies exist: Shows count and skips creation
- If no companies exist: Creates a default company with basic information
- Returns detailed information about the action taken

**Default company details:**
- **Name**: "Default Company"
- **Number**: "COMP-001"
- **Address**: "Default Street 1, 1000 AA Amsterdam"
- **Country**: Netherlands (nl)
- **Phone**: "+31 20 123 4567"
- **Status**: Active
- **Created by**: "System"

### Example Output

```bash
# When no companies exist
✓ Default company created successfully
  Company: Default Company
  Number: COMP-001
  City: Amsterdam

# When companies already exist
✓ Found 3 existing companies in database
```

## manta:refresh

Refreshes the package by clearing cache and rebuilding dependencies.

### Usage

```bash
php artisan manta:refresh

# Without npm build
php artisan manta:refresh --no-build
```

### Options

- `--no-build` : Do not run npm run build
