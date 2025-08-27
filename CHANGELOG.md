# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.5] - 2025-08-27

### Changed
- Versienummer bijgewerkt in `composer.json` naar 1.1.5
- Changelog aangevuld voor nieuwe release

## [1.1.4] - 2025-08-26

### Added
- **ModuleSettingsService**: Smart create/update logic for module settings
- **Staff module improvements**: Enhanced route handling with module_routes array

### Changed
- **ModuleSettingsService**: Only creates new modules if they don't exist
- **ModuleSettingsService**: Updates only empty fields or incorrect array types to preserve existing data
- **Staff components**: Migrated from hardcoded route names to dynamic module_routes array
- **Staff settings**: Updated tabtitle from 'title' to 'firstname' for better user display

### Technical Improvements
- **Performance**: Reduced unnecessary database writes in ModuleSettingsService
- **Data integrity**: Preserves existing module configurations during updates
- **Type safety**: Automatic correction of fields that should be arrays but aren't
- **Route flexibility**: Dynamic route handling in Staff module components

## [1.1.3] - 2025-08-26

### Added
- **SeoTrait improvements**: Enhanced loadSeoData() method with database priority over hardcoded values
- **Database-driven SEO**: SEO waarden kunnen nu overschreven worden via CMS database
- **Automatic SEO record creation**: Creates Routeseo records automatically when they don't exist

### Changed
- **SEO priority logic**: Database waarden hebben voorrang op hardcoded waarden in SeoTrait
- **SeoTrait loadSeoData()**: Improved logic to respect existing programmatic SEO values when database is empty
- **Performance optimization**: Removed GetRouteSeo middleware dependency for better performance

### Removed
- **GetRouteSeo middleware**: Functionality moved to SeoTrait for better component-level control

### Technical Improvements
- Enhanced SEO architecture with component-level control
- Better separation of concerns between middleware and traits
- Improved database query efficiency for SEO data

## [1.1.2] - 2025-08-26

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
- New Livewire component registrations in FluxCMSServiceProvider:
  - CMS components: `CmsAlert`, `CmsDashboard`, `CmsFooter`, `CmsModuleTranslations`, `CmsNumbers`, `CmsSandbox`
  - Web components: `WebDashboard`
  - Website components: `WebsiteInlineEditor`, `WebsiteTranslator`

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
- Livewire component naming updates:
  - `WebsiteTranslator` component registration renamed to `manta-cms::website.website-translator`
  - Fixed namespace import in `WebsiteTranslator` from `Manta\Models\Translation` to `Manta\FluxCMS\Models\Translation`

## [0.1.0] - 2025-07-15
### Added
- Basic structure for the Laravel Flux CMS package
