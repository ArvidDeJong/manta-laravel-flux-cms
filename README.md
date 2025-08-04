# Manta Laravel Flux CMS

A modern CMS package for Laravel 12, built with Livewire 3 and FluxUI.

Part of the [Manta](https://manta.website) ecosystem.

## ⚡ Quick Start

### Automatic Installation (Recommended)

```bash
# Install the package
composer require manta/laravel-manta-cms

# Run the complete installation (includes migrations, seeding, and setup)
php artisan manta:install --with-migrations
```

✅ **What this does:**
- Registers the service provider
- Publishes configuration files
- Publishes views and assets
- Runs database migrations
- **Creates a default company** (if none exist)
- Synchronizes routes
- Clears cache

### Manual Installation

```bash
# Install the package
composer require manta/laravel-manta-cms

# Publish and run migrations
php artisan vendor:publish --provider="Manta\FluxCMS\FluxCMSServiceProvider"
php artisan migrate

# Create default company (if needed)
php artisan manta:seed-company

# Sync routes (optional)
php artisan manta:sync-routes --prefix=cms
```

## 📚 Documentation

Comprehensive documentation organized in modular files for easy navigation:

| Topic                                                  | Description                                  |
| ------------------------------------------------------ | -------------------------------------------- |
| [**Installation**](docs/installation.md)               | Detailed installation instructions and setup |
| [**Configuration**](docs/configuration.md)             | Environment variables, middleware and guards |
| [**Features**](docs/features.md)                       | Complete feature overview and benefits       |
| [**Models**](docs/models.md)                           | Eloquent models and database structure       |
| [**Artisan Commands**](docs/commands.md)               | All available `manta:` commands              |
| [**Livewire Components**](docs/livewire-components.md) | Interactive components with FluxUI           |
| [**Helper Functions**](docs/helpers.md)                | Available helper functions and usage         |
| [**Blade Components**](docs/blade-components.md)       | Blade components and templates               |
| [**Troubleshooting**](docs/troubleshooting.md)         | Common issues and solutions                  |
| [**Development**](docs/development.md)                 | Contributing to the package                  |

**International Accessibility**: All documentation and command output translated to English for global accessibility.

## Requirements

- PHP 8.2 or higher
- Laravel 12.x
- Livewire 3.x

## 👨‍💻 Author

**Arvid de Jong**  
📧 [info@arvid.nl](mailto:info@arvid.nl)  
🌐 [manta.website](https://manta.website)

Built with ❤️ for the Laravel community.

## License

This package is open-source software licensed under the [MIT license](LICENSE.md).
