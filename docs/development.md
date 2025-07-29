# Development

This page contains information for developers who want to contribute to the Manta Laravel Flux CMS package.

## Development Setup

### Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- Laravel 12.x test project

### Local Development

1. Clone the repository:
   ```bash
   git clone https://github.com/manta/laravel-manta-cms.git
   cd laravel-manta-cms
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Link the package locally in a Laravel project:
   ```bash
   # In your Laravel project
   composer config repositories.manta-cms path "../path/to/laravel-manta-cms"
   composer require manta/laravel-manta-cms:@dev
   ```

### Testing

Run tests with PHPUnit:

```bash
# All tests
./vendor/bin/phpunit

# Specific test
./vendor/bin/phpunit tests/Unit/MantaRouteTest.php

# With coverage
./vendor/bin/phpunit --coverage-html coverage
```

### Code Style

The package uses Laravel Pint for code formatting:

```bash
# Check code style
./vendor/bin/pint --test

# Fix code style
./vendor/bin/pint
```

### Static Analysis

Use PHPStan for static analysis:

```bash
./vendor/bin/phpstan analyse
```

## Package Structure

```
src/
├── Console/
│   └── Commands/           # Artisan commands
├── Http/
│   ├── Controllers/        # Controllers
│   ├── Middleware/         # Middleware
│   └── Requests/          # Form requests
├── Models/                # Eloquent models
├── Providers/             # Service providers
└── Views/                 # Blade templates

database/
├── migrations/            # Database migrations
└── seeders/              # Database seeders

config/
└── flux-cms.php          # Package configuration

resources/
├── views/                # Blade views
├── css/                  # Stylesheets
└── js/                   # JavaScript

tests/
├── Unit/                 # Unit tests
├── Feature/              # Feature tests
└── TestCase.php          # Base test case
```

## Adding New Features

### 1. Adding a Model

```bash
# Create model
php artisan make:model Models/MantaExample

# Create migration
php artisan make:migration create_manta_examples_table
```

Follow existing conventions:
- Use `Manta` prefix for model names
- Implement `HasFactory` trait
- Add `created_by`, `updated_by` fields
- Use query scopes where relevant

### 2. Adding a Command

```bash
php artisan make:command ExampleCommand
```

Conventions:
- Use `manta:` prefix for command signature
- Place in `src/Console/Commands/`
- Register in `FluxCMSServiceProvider`
- Add documentation to `docs/commands.md`

### 3. Adding a Livewire Component

```bash
php artisan make:livewire ExampleComponent
```

Conventions:
- Use FluxUI components
- Implement proper validation
- Follow Livewire 3 best practices
- Test the component

### 4. Adding a Migration

Conventions:
- Use timestamp prefix: `2023_01_01_000000_`
- Add `created_by`, `updated_by` fields
- Use consistent naming
- Test up() and down() methods

## Testing Guidelines

### Unit Tests

Test individual methods and classes:

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Manta\FluxCMS\Models\MantaRoute;

class MantaRouteTest extends TestCase
{
    public function test_can_create_route()
    {
        $route = MantaRoute::create([
            'uri' => 'test/route',
            'name' => 'test.route',
            'prefix' => 'test',
            'active' => true
        ]);

        $this->assertInstanceOf(MantaRoute::class, $route);
        $this->assertEquals('test/route', $route->uri);
    }

    public function test_active_scope_filters_correctly()
    {
        MantaRoute::factory()->create(['active' => true]);
        MantaRoute::factory()->create(['active' => false]);

        $activeRoutes = MantaRoute::active()->get();

        $this->assertCount(1, $activeRoutes);
        $this->assertTrue($activeRoutes->first()->active);
    }
}
```

### Feature Tests

Test complete features:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Manta\FluxCMS\Models\MantaRoute;

class SyncRoutesCommandTest extends TestCase
{
    public function test_sync_routes_command_works()
    {
        $this->artisan('manta:sync-routes')
            ->expectsOutput('Routes synchronized!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('manta_routes', [
            'name' => 'cms.dashboard'
        ]);
    }

    public function test_sync_routes_with_prefix_filter()
    {
        $this->artisan('manta:sync-routes --prefix=cms')
            ->assertExitCode(0);

        $routes = MantaRoute::where('prefix', 'cms')->get();
        $this->assertGreaterThan(0, $routes->count());
    }
}
```

### Livewire Tests

Test Livewire components:

```php
<?php

namespace Tests\Feature;

use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\UserTable;

class UserTableTest extends TestCase
{
    public function test_can_render_component()
    {
        Livewire::test(UserTable::class)
            ->assertStatus(200);
    }

    public function test_can_search_users()
    {
        $user = User::factory()->create(['name' => 'John Doe']);

        Livewire::test(UserTable::class)
            ->set('search', 'John')
            ->assertSee('John Doe');
    }
}
```

## Database Conventions

### Naming

- Tables: `manta_` prefix, snake_case, plural
- Columns: snake_case
- Foreign keys: `{model}_id`
- Timestamps: `created_at`, `updated_at`
- Audit fields: `created_by`, `updated_by`

### Standard Columns

Add these columns to every table:

```php
$table->id();
$table->unsignedBigInteger('created_by')->nullable();
$table->unsignedBigInteger('updated_by')->nullable();
$table->timestamps();

// For soft deletes (if needed)
$table->unsignedBigInteger('deleted_by')->nullable();
$table->softDeletes();
```

## Code Style Guidelines

### PHP

- Follow PSR-12 standard
- Use type hints where possible
- Document complex methods with PHPDoc
- Use meaningful variable names

```php
<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * MantaRoute model for managing Laravel routes
 * 
 * @property int $id
 * @property string $uri
 * @property string $name
 * @property string $prefix
 * @property bool $active
 */
class MantaRoute extends Model
{
    protected $fillable = [
        'uri',
        'name', 
        'prefix',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Scope for active routes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
```

### Blade Templates

- Use FluxUI components
- Consistent indenting (4 spaces)
- Meaningful component names

```blade
<div class="space-y-6">
    <flux:heading size="lg">
        {{ $title }}
    </flux:heading>

    <flux:table>
        <flux:columns>
            <flux:column>Name</flux:column>
            <flux:column>Status</flux:column>
        </flux:columns>
        
        <flux:rows>
            @foreach($items as $item)
                <flux:row wire:key="{{ $item->id }}">
                    <flux:cell>{{ $item->name }}</flux:cell>
                    <flux:cell>
                        <flux:badge :variant="$item->active ? 'success' : 'danger'">
                            {{ $item->active ? 'Active' : 'Inactive' }}
                        </flux:badge>
                    </flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</div>
```

## Release Process

### Versioning

The package follows [Semantic Versioning](https://semver.org/):

- **MAJOR**: Breaking changes
- **MINOR**: New features (backwards compatible)
- **PATCH**: Bug fixes (backwards compatible)

### Release Checklist

1. Update `CHANGELOG.md`
2. Run all tests
3. Update documentation
4. Tag the release
5. Publish to Packagist

```bash
# Tests
./vendor/bin/phpunit
./vendor/bin/pint --test
./vendor/bin/phpstan analyse

# Tag release
git tag v1.2.3
git push origin v1.2.3
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Write tests for new functionality
4. Make sure all tests pass
5. Update documentation
6. Create a pull request

### Pull Request Guidelines

- Clear title and description
- Link to related issues
- Screenshots for UI changes
- Tests for new functionality
- Update CHANGELOG.md
