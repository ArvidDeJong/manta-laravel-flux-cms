# Manta Laravel Flux CMS

Een krachtige Laravel 12 CMS package gebouwd met Livewire 3 en FluxUI.

## Overzicht

Manta Laravel Flux CMS biedt een complete content management oplossing voor Laravel applicaties, inclusief:

- **Staff Management**: Gebruikersbeheer met rollen en rechten
- **Content Management**: Pagina's, uploads en media beheer
- **SEO Optimalisatie**: Uitgebreide SEO tools en metadata beheer
- **Multi-language Support**: Volledige meertaligheid ondersteuning
- **FluxUI Integration**: Moderne UI componenten
- **Livewire 3**: Reactieve interfaces zonder JavaScript

## Installatie

```bash
composer require darvis/manta-laravel-flux-cms
```

## Configuratie

Publiceer de configuratie bestanden:

```bash
php artisan vendor:publish --provider="Manta\FluxCMS\FluxCMSServiceProvider"
```

Voer de migraties uit:

```bash
php artisan migrate
```

## Hoofdfuncties

### Staff Management

- Gebruikersbeheer met staff guard
- Rollen en rechten systeem
- Password reset functionaliteit
- Login/logout flows

### Content Management

- Upload beheer met image processing
- PDF verwerking en pagina extractie
- File management systeem
- Media bibliotheek

### SEO Functionaliteit

Het CMS bevat uitgebreide SEO tools:

- **Routeseo Model**: Database-driven SEO metadata per route
- **SeoTrait**: Herbruikbare trait voor Livewire componenten
- **GetRouteSeo Middleware**: Automatische SEO data loading
- **Multi-language SEO**: Verschillende SEO data per taal

Zie [SeoTrait Documentatie](seo-trait.md) voor gedetailleerde uitleg.

### Navigation Management

- **MantaNav Model**: Dynamische navigatie items
- **Module-based Navigation**: Georganiseerd per module type
- **Sorteerbare Items**: Drag & drop interface
- **Multi-level Support**: Hierarchische navigatie

## Traits

### SeoTrait

De `SeoTrait` biedt eenvoudige SEO functionaliteit voor Livewire componenten door data uit de `Routeseo` model te laden.

**Gebruik:**
```php
use Manta\FluxCMS\Traits\SeoTrait;

class HomePage extends Component
{
    use SeoTrait;
    
    public function mount()
    {
        $this->loadSeoData();
    }
    
    public function render()
    {
        return view('livewire.pages.home')
            ->layoutData($this->getSeoLayoutData());
    }
}
```

**Beschikbare methoden:**
- `loadSeoData()` - Laadt SEO data voor huidige route
- `getSeoLayoutData()` - Retourneert SEO data voor layout
- `getSeoTitle()` - Retourneert SEO titel
- `getSeoDescription()` - Retourneert SEO beschrijving

Zie [SeoTrait documentatie](seo-trait.md) voor meer details.

### HasSeo

De `HasSeo` trait biedt uitgebreide SEO functionaliteit voor Livewire componenten, inclusief meta tags, Open Graph, Twitter Cards en Schema.org structured data.

**Gebruik:**
```php
use Manta\FluxCMS\Traits\HasSeo;

class HomePage extends Component
{
    use HasSeo;
    
    public function mount()
    {
        $this->setSeoTitle('Welkom bij TUHO Groep')
             ->setSeoDescription('Ontdek onze innovatieve oplossingen')
             ->setSeoKeywords('TUHO Groep, homepage, bedrijfsgroei');
    }
    
    public function render()
    {
        return view('livewire.pages.home')
            ->layoutData($this->getSeoData());
    }
}
```

**Belangrijkste methoden:**
- `setSeoTitle()` - Stelt SEO titel in
- `setSeoDescription()` - Stelt SEO beschrijving in
- `setOpenGraph()` - Stelt Open Graph data in
- `setTwitterCard()` - Stelt Twitter Card data in
- `setStructuredData()` - Stelt Schema.org data in
- `setSeoData()` - Stelt alle SEO data in één keer in
- `getSeoData()` - Retourneert alle SEO data voor layout

Zie [HasSeo documentatie](has-seo-trait.md) voor uitgebreide voorbeelden en alle beschikbare methoden.

### HasUploadsTrait

Voor file upload functionaliteit:

```php
use Manta\FluxCMS\Traits\HasUploadsTrait;

class MyModel extends Model
{
    use HasUploadsTrait;
    
    // Automatische upload relaties
}
```

## Models

### Core Models

- **Staff**: Medewerker/gebruiker management
- **Company**: Bedrijfsinformatie
- **Upload**: File en media management
- **Routeseo**: SEO metadata per route
- **MantaNav**: Navigatie items
- **Option**: Configuratie opties

### Relationships

Alle models ondersteunen:
- Soft deletes
- Audit trails (created_by, updated_by, deleted_by)
- Multi-company support
- Localization

## Middleware

### GetRouteSeo

Automatische SEO data loading voor routes:

```php
Route::middleware(['web', \Manta\FluxCMS\Middleware\GetRouteSeo::class])
    ->name('website.')
    ->group(function () {
        // Routes met automatische SEO
    });
```

### StaffAuthenticate

Authenticatie voor staff gebruikers:

```php
Route::middleware(['web', 'auth:staff'])
    ->prefix('cms')
    ->group(function () {
        // CMS routes
    });
```

## Livewire Componenten

### Auth Components

- `AuthStaff\LoginForm`: Staff login
- `AuthStaff\ForgotPassword`: Password reset
- `AuthStaff\ResetPassword`: Password reset form
- `AuthStaff\Logout`: Logout functionaliteit

### CMS Components

- `Dashboard`: CMS dashboard
- `Staff\StaffList`: Medewerker overzicht
- `Upload\UploadList`: Media bibliotheek
- `MantaNav\MantaNavList`: Navigatie beheer

## Configuratie

### Auth Guards

Het package registreert automatisch een `staff` guard:

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

'passwords' => [
    'staff' => [
        'provider' => 'staff',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

### Routes

CMS routes zijn beschikbaar onder `/cms` prefix:

- `/cms` - Dashboard
- `/cms/staff/login` - Staff login
- `/cms/medewerkers` - Staff management
- `/cms/upload` - Media management

## Helpers

### Global Functions

```php
// Configuratie ophalen
manta_option('key', 'default');

// Asset URLs
manta_cms_asset('/path/to/asset.js');
manta_cms_lib('/library/file.js');

// Locale functies
getLocaleManta(); // Default locale
getLocalesManta(); // Alle locales
```

### Blade Components

```blade
<!-- CMS Header -->
<x-manta.cms.header-flux-php />

<!-- Upload display -->
<x-manta.upload :upload="$upload" />

<!-- Navigation -->
<x-manta.nav />
```

## Development

### Testing

```bash
vendor/bin/phpunit
```

### Code Style

```bash
vendor/bin/pint
```

## Licentie

MIT License. Zie [LICENSE](LICENSE) voor details.

## Support

Voor vragen en support:
- Email: info@arvid.nl
- Website: https://arvid.nl

## Changelog

Zie [CHANGELOG.md](../CHANGELOG.md) voor versie geschiedenis.
