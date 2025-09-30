# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.1] - 2025-09-30

### Fixed
- **Route Issues**: Fixed `Route [logout] not defined` error in verify-email component by correcting route reference to `flux-cms.account.logout`
- **Dependency Resolution**: Fixed `Unable to resolve dependency [Parameter #0 [ <required> $token ]]` error in ResetPassword components
- **Password Reset Routes**: Added token parameter to reset-password routes for both staff and account authentication
- **Component Flexibility**: Made token parameter optional in ResetPassword components with multiple fallback methods

### Changed
- **Reset Password Routes**: Updated routes to include optional token parameter:
  - `/staff/reset-password/{token?}`
  - `/account/reset-password/{token?}`
- **ResetPassword Components**: Enhanced mount method to handle token from multiple sources (route, query, request)
- **Authentication Flow**: Improved password reset functionality for both staff and user authentication

### Technical Improvements
- **Error Handling**: Better error handling in authentication components
- **Route Consistency**: Standardized route patterns across staff and account authentication
- **Component Robustness**: More flexible parameter handling in Livewire components

## [1.2.0] - 2025-09-30

### Added
- **Staff Authentication**: Enhanced staff existence checks and improved login logging for staff authentication
- **Sitemap Filtering**: Uitgebreide route exclusies en patronen voor betere sitemap filtering
- **Install Command**: Enhanced installation command with additional functionality

### Changed
- **Default Views**: Migrated default views to FluxUI components and improved layout structure
- **Tabs Component**: Updated manta-default-tabs.blade.php with improved functionality
- **MantaTrait**: Added new properties and methods for enhanced functionality
- **WithSortingTrait**: Improved and optimized sorting functionality
- **Authentication Views**: Updated auth views (login, register, forgot-password, reset-password) to use FluxUI components

### Improved
- **Layout Structure**: Better organization of default view components
- **Component Architecture**: Enhanced separation of concerns in authentication components
- **Performance**: Optimized WithSortingTrait for better performance

### Technical Improvements
- **FluxUI Migration**: Systematic migration from legacy components to FluxUI
- **Code Organization**: Better structure for authentication and default view components
- **Trait Optimization**: Improved efficiency in sorting and authentication traits

## [1.1.9] - 2025-09-10

### Added
- **WebflowImage Component**: Auto-generate srcset functionality for Webflow images using basePath
- **OpenAI Integration**: Added `openaiImageAdd` property to MantaTrait for image selection control
- **OpenAI Form**: Added checkbox to allow adding OpenAI generated images to messages

### Changed
- **WebflowImage Component**: Enhanced fallback image handling and srcset generation
- **OpenAI Image Gallery**: Improved conditional display of OpenAI images gallery
- **Form Field List**: Updated styling and layout improvements
- **Navigation**: Minor improvements to MantaNavList component
- **Version Management**: Removed version field from `composer.json` for better package management
- **Git Tags**: Standardized all version tags to use v-prefix format (v1.0.0, v1.1.0, etc.)

### Fixed
- **WebflowImage Component**: Better handling of empty src values with fallback images
- **OpenAI Images**: Fixed image gallery display logic to only show when images exist
- **Version Conflicts**: Resolved inconsistent tag formats that could cause conflicts with package managers

## [1.1.8] - 2025-09-06

### Added
- **MantaNavList**: Added `updateType` method for dynamic navigation item type updates
- **Staff Model**: Implemented password reset functionality with `CanResetPassword` interface and trait
- **HeaderFlux Component**: Added role-based access control for tools and dev modules

### Changed
- **Navigation Management**: Enhanced MantaNavList with real-time type switching capabilities
- **Staff Authentication**: Staff users can now reset their passwords through standard Laravel mechanisms
- **Admin Interface**: Tools and dev modules now respect user admin/developer permissions

### Security
- **Access Control**: Restricted tools module access to admin users only
- **Permission System**: Dev modules now only visible to users with developer permissions

## [1.1.7] - 2025-08-28

### Added
- **Company Model**: Added URL and website fields to Company model
- **Related Components**: Updated related components to support new Company fields

## [1.1.6] - 2025-08-28

### Added
- **OpenAI Image Gallery UI**: Improved user interface for OpenAI generated image gallery

### Changed
- **SEO Fields**: Made SEO title and description fields nullable for better flexibility
- Version field removed from `composer.json` for better package management
- Changelog content translated from Dutch to English for international accessibility

## [1.1.5] - 2025-08-27

### Added
- **OpenAI Image Generation**: New functionality for AI-generated images
- **UI Controls**: Interface for managing image generation parameters
- **Display Gallery**: Gallery view for generated images

### Changed
- Version number updated in `composer.json` to 1.1.5
- Changelog updated for new release

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
- **Database-driven SEO**: SEO values can now be overridden via CMS database
- **Automatic SEO record creation**: Creates Routeseo records automatically when they don't exist

### Changed
- **SEO priority logic**: Database values take precedence over hardcoded values in SeoTrait
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
