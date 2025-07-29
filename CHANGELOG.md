# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- MantaRoute model for managing Laravel routes
- Migration for manta_routes table with fields: uri, name, prefix, active
- `manta:sync-routes` command for synchronizing Laravel routes to database
  - `--prefix` option for filtering on route prefix
  - `--clear` option for removing existing routes
- Initial project structure
- Database migrations for all models: Audit, Firewall, Iplist, Mailtrap, Option, Routeseo, Company, Staff, StaffLog, Upload (User), Contactperson, UserLog, Userregister, Translation, User
- Enhanced Eloquent models with type hints, query scopes, relationships and PHPDoc annotations
- ServiceProvider registration for all models
- Artisan command for cache clearing and dependency rebuilding
- Comprehensive documentation structure with modular markdown files:
  - `docs/installation.md` - Installation and setup instructions
  - `docs/commands.md` - Artisan commands documentation
  - `docs/models.md` - Database models and relationships
  - `docs/configuration.md` - Configuration options and environment variables
  - `docs/livewire-components.md` - Livewire 3 components documentation
  - `docs/troubleshooting.md` - Common issues and solutions
  - `docs/development.md` - Development guidelines and contribution guide

### Changed
- All Artisan commands renamed to `manta:` prefix for consistency:
  - `flux:copy-libraries` → `manta:copy-libraries`
  - `flux:create-staff` → `manta:create-staff`
  - `flux:install` → `manta:install`
  - `flux:refresh` → `manta:refresh`
  - `app:create-user` → `manta:create-user`
- All Artisan command output and messages translated from Dutch to English for international accessibility
- Enhanced `manta:install` command with automatic route synchronization during installation
- Complete documentation translation from Dutch to English:
  - All README.md content translated for international accessibility
  - All documentation files in `docs/` directory translated to English
  - Project guidelines and development documentation in English
  - Consistent English terminology and professional presentation throughout

## [0.1.0] - 2025-07-15
### Added
- Basic structure for the Laravel Flux CMS package
