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

Creates a default company and optionally generates additional sample companies. This is automatically called during `manta:install` but can also be run manually to generate test data.

### Usage

```bash
# Seed default company (if none exist)
php artisan manta:seed-company

# Generate 5 companies
php artisan manta:seed-company --count=5

# Generate 10 companies
php artisan manta:seed-company --count=10
```

### Options

- `--count=N` : Number of companies to generate (default: 1)

### Functionality

The command:
- Checks if any companies exist in the database
- Creates a default company if none exist
- Generates additional companies with realistic sample data when `--count` > 1
- Uses diverse company names, cities, and contact information
- Ensures unique company names and numbers
- Returns detailed information about created companies

**Default company details:**
- **Name**: "Default Company"
- **Number**: "COMP-001"
- **Address**: "Default Street 1, 1000 AA Amsterdam"
- **Country**: Netherlands (nl)
- **Phone**: "+31 20 123 4567"
- **Status**: Active
- **Created by**: "System"

**Sample company data includes:**
- Realistic Dutch company names (Tech Solutions BV, Green Energy Corp, etc.)
- Major Dutch cities with correct postal codes
- Sequential company numbers (COMP-001, COMP-002, etc.)
- Generated phone numbers and addresses

### Example Output

```bash
# When no companies exist (single company)
Running Company Seeder (generating 1 companies)...
✓ Created 1 companies. Total companies in database: 1
  📢 Default Company (COMP-001) - Amsterdam

# When generating multiple companies
Running Company Seeder (generating 5 companies)...
✓ Created 5 companies. Total companies in database: 6
  📢 Food & Beverage Co (COMP-002) - Utrecht
  📢 Construction Masters (COMP-004) - Almere
  📢 Consulting Group (COMP-006) - Amsterdam
  📢 Construction Masters 1 (COMP-008) - Groningen
  📢 Software Development (COMP-010) - Almere

# When companies already exist
✓ Found 3 existing companies in database
💡 Use --count=5 to generate 5 additional companies anyway
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

## 🌱 Creating Module Seeders

For Manta modules, you can create seeders that follow a standardized pattern. This ensures consistency across all modules and provides users with familiar command options.

### Seeder Structure

A proper Manta module seeder should:

1. **Extend `Illuminate\Console\Command`**
2. **Use standardized command signature with common options**
3. **Include sample data seeding**
4. **Support navigation item seeding**
5. **Provide user-friendly output with emojis and progress indicators**

### Example: Creating a Page Seeder

Here's how to create a seeder for a module like `darvis/manta-page`:

#### Step 1: Create the Command Class

```php
<?php

namespace Darvis\MantaPage\Console\Commands;

use Darvis\MantaPage\Models\Page;
use Illuminate\Console\Command;

class SeedPageCommand extends Command
{
    protected $signature = 'manta-page:seed 
                            {--force : Force seeding even if pages already exist}
                            {--fresh : Delete existing pages before seeding}
                            {--with-navigation : Also seed navigation items for page management}';

    protected $description = 'Seed the database with sample pages';

    public function handle()
    {
        $this->info('🌱 Seeding Manta Pages...');
        $this->newLine();

        // Check existing items
        $existingCount = Page::count();
        
        if ($existingCount > 0 && !$this->option('force') && !$this->option('fresh')) {
            $this->warn("⚠️  Found {$existingCount} existing pages.");
            
            if (!$this->confirm('Do you want to continue seeding? This will add more items.', false)) {
                $this->info('Seeding cancelled.');
                return self::SUCCESS;
            }
        }

        // Handle fresh option
        if ($this->option('fresh')) {
            if ($this->confirm('This will delete ALL existing pages. Are you sure?', false)) {
                $this->info('🗑️  Deleting existing pages...');
                Page::truncate();
                $this->line('   ✅ Existing pages deleted');
            } else {
                $this->info('Fresh seeding cancelled.');
                return self::SUCCESS;
            }
        }

        // Run the seeder
        try {
            $this->seedPageItems();
            
            $totalCount = Page::count();
            $this->newLine();
            $this->info("🎉 Page seeding completed successfully!");
            $this->line("   📊 Total pages in database: {$totalCount}");
            
        } catch (\Exception $e) {
            $this->error('❌ Error during seeding: ' . $e->getMessage());
            return self::FAILURE;
        }

        // Seed navigation if requested
        if ($this->option('with-navigation')) {
            $this->seedNavigation();
        }

        return self::SUCCESS;
    }

    private function seedPageItems(): void
    {
        // Your seeding logic here
    }

    private function seedNavigation(): void
    {
        // Navigation seeding logic here
    }
}
```

#### Step 2: Register the Command

Add the command to your module's ServiceProvider:

```php
// In your ModuleServiceProvider.php
if ($this->app->runningInConsole()) {
    $this->commands([
        \Darvis\MantaPage\Console\Commands\SeedPageCommand::class,
    ]);
}
```

### Standard Command Options

All module seeders should support these options:

- `--force` : Force seeding even if items already exist
- `--fresh` : Delete existing items before seeding (with confirmation)
- `--with-navigation` : Also seed navigation items for module management

### Navigation Items Structure

When seeding navigation items, use this structure:

```php
private function seedModuleNavigation(): void
{
    $navItems = [
        [
            'title' => 'Module Name',
            'route' => 'module.list',
            'sort' => 5, // Adjust as needed
            'type' => 'module', // Important: use 'module', not 'content'
            'description' => 'Manage module items'
        ]
    ];

    $MantaNav = '\Manta\FluxCMS\Models\MantaNav';
    
    foreach ($navItems as $item) {
        $existingNav = $MantaNav::where('route', $item['route'])
            ->where('locale', 'nl')
            ->first();

        if (!$existingNav) {
            $MantaNav::create([
                'created_by' => 'Module Seeder',
                'company_id' => 1,
                'host' => request()->getHost() ?? 'localhost',
                'locale' => 'nl',
                'active' => true,
                'sort' => $item['sort'],
                'title' => $item['title'],
                'route' => $item['route'],
                'type' => $item['type'],
                'data' => json_encode([
                    'description' => $item['description'],
                    'icon' => 'document-text', // Choose appropriate icon
                    'module' => 'manta-module-name'
                ]),
            ]);
        }
    }
}
```

### Best Practices

1. **Consistent Naming**: Use `manta-{module}:seed` as command name
2. **User-Friendly Output**: Use emojis and clear messages
3. **Safety First**: Always confirm destructive actions
4. **Error Handling**: Wrap seeding in try-catch blocks
5. **Progress Indicators**: Show what's happening at each step
6. **Flexible Options**: Support force, fresh, and navigation options
7. **Navigation Type**: Always use `'type' => 'module'` for navigation items

### Testing Your Seeder

```bash
# Test basic seeding
php artisan manta-module:seed

# Test with all options
php artisan manta-module:seed --fresh --force --with-navigation

# Check help
php artisan manta-module:seed --help
```
