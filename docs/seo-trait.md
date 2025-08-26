# SeoTrait Documentatie

De `SeoTrait` biedt een eenvoudige manier om SEO functionaliteit toe te voegen aan Livewire componenten in combinatie met het Routeseo model.

## Installatie

De SeoTrait is onderdeel van het `darvis/manta-laravel-flux-cms` package en wordt automatisch geladen.

## Basis Gebruik

### 1. Trait toevoegen aan component

```php
<?php

namespace App\Livewire\Pages;

use Livewire\Component;
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

### 2. Middleware toevoegen aan routes

Voor automatische SEO data loading, voeg de `GetRouteSeo` middleware toe aan je route groep:

```php
Route::middleware(['web', \Manta\FluxCMS\Middleware\GetRouteSeo::class])
    ->name('website.')
    ->group(function () {
        Route::get('/', HomePage::class)->name('home');
        Route::get('/contact', ContactPage::class)->name('contact');
        // Meer routes...
    });
```

## Beschikbare Properties

De trait voegt de volgende properties toe aan je component:

- `$seoTitle` - De SEO titel voor de pagina
- `$seoDescription` - De SEO beschrijving voor de pagina  
- `$routeseo` - Het volledige Routeseo model object

## Beschikbare Methoden

### `loadSeoData()`

Laadt SEO data voor de huidige route uit de database.

```php
public function mount()
{
    $this->loadSeoData();
}
```

**Functionaliteit:**
- Zoekt eerst naar SEO data voor de huidige locale
- Valt terug op default locale als niet gevonden
- Maakt automatisch een nieuw Routeseo record aan als het niet bestaat
- Stelt `$seoTitle` en `$seoDescription` properties in

### `getSeoLayoutData()`

Geeft een array terug met SEO data voor gebruik in layout data.

```php
public function render()
{
    return view('livewire.pages.home')
        ->layoutData($this->getSeoLayoutData());
}
```

**Geretourneerde data:**
```php
[
    'title' => $this->seoTitle,
    'seo_title' => $this->seoTitle,
    'seo_description' => $this->seoDescription,
    'routeseo' => $this->routeseo,
]
```

## Routeseo Model

Het Routeseo model slaat SEO metadata op per route en locale:

### Database Structuur

```php
Schema::create('routeseos', function (Blueprint $table) {
    $table->id();
    $table->string('route'); // Route naam (bijv. 'website.home')
    $table->string('locale'); // Taal code (bijv. 'nl', 'en')
    $table->string('title')->nullable();
    $table->string('seo_title')->nullable();
    $table->text('seo_description')->nullable();
    $table->json('data')->nullable(); // Extra SEO data
    $table->timestamps();
    $table->softDeletes();
});
```

### Model Scopes

```php
// Zoek op route naam
Routeseo::byRoute('website.home')->first();

// Zoek op locale
Routeseo::byLocale('nl')->get();

// Zoek in SEO content
Routeseo::search('homepage')->get();
```

## Voorbeelden

### Basis implementatie

```php
use Manta\FluxCMS\Traits\SeoTrait;

class ContactPage extends Component
{
    use SeoTrait;
    
    public function mount()
    {
        $this->loadSeoData();
    }
    
    public function render()
    {
        return view('livewire.pages.contact')
            ->layoutData(array_merge([
                'data_wf_page' => 'contact-page-id',
            ], $this->getSeoLayoutData()));
    }
}
```

### Handmatige SEO data override

```php
public function mount()
{
    $this->loadSeoData();
    
    // Override voor specifieke pagina
    if ($this->specialCondition) {
        $this->seoTitle = 'Speciale Titel';
        $this->seoDescription = 'Speciale beschrijving';
    }
}
```

### Dynamische SEO data

```php
public function mount($slug)
{
    $this->loadSeoData();
    
    $article = Article::where('slug', $slug)->first();
    
    if ($article) {
        $this->seoTitle = $article->title;
        $this->seoDescription = $article->excerpt;
    }
}
```

## Layout Integratie

In je layout template kun je de SEO data gebruiken:

```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ $seo_title ?? $title ?? config('app.name') }}</title>
    
    @if(isset($seo_description) && $seo_description)
        <meta name="description" content="{{ $seo_description }}">
    @endif
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $seo_title ?? $title ?? config('app.name') }}">
    @if(isset($seo_description) && $seo_description)
        <meta property="og:description" content="{{ $seo_description }}">
    @endif
    
    <!-- Twitter Card -->
    <meta name="twitter:title" content="{{ $seo_title ?? $title ?? config('app.name') }}">
    @if(isset($seo_description) && $seo_description)
        <meta name="twitter:description" content="{{ $seo_description }}">
    @endif
</head>
<body>
    {{ $slot }}
</body>
</html>
```

## Meertaligheid

De SeoTrait ondersteunt automatisch meertaligheid:

1. Zoekt eerst naar SEO data voor huidige locale (`app()->getLocale()`)
2. Valt terug op default locale (`config('app.locale')`)
3. Maakt nieuwe records aan met juiste locale

```php
// Voorbeeld: Nederlandse en Engelse SEO data
Routeseo::create([
    'route' => 'website.home',
    'locale' => 'nl',
    'seo_title' => 'Welkom bij TUHO Groep',
    'seo_description' => 'Nederlandse beschrijving',
]);

Routeseo::create([
    'route' => 'website.home', 
    'locale' => 'en',
    'seo_title' => 'Welcome to TUHO Group',
    'seo_description' => 'English description',
]);
```

## Best Practices

1. **Altijd loadSeoData() aanroepen** in de `mount()` methode
2. **Layout data combineren** met `array_merge()` voor extra data
3. **Fallback waarden** instellen in je layout templates
4. **SEO data valideren** in je CMS voor optimale resultaten
5. **Consistent route naming** gebruiken (bijv. `website.` prefix)

## Troubleshooting

### SEO data wordt niet geladen

Controleer of:
- De `GetRouteSeo` middleware is toegevoegd aan je routes
- De route naam correct is (gebruik `Route::currentRouteName()` om te debuggen)
- Het Routeseo record bestaat in de database

### Layout data wordt niet doorgegeven

Zorg ervoor dat je `getSeoLayoutData()` aanroept in je `render()` methode:

```php
return view('...')
    ->layoutData($this->getSeoLayoutData());
```
