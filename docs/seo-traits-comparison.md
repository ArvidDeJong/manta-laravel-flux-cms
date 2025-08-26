# SeoTrait vs HasSeo - Inconsistenties en Aanbevelingen

## 🔍 Huidige Inconsistenties

### Property Namen
| Aspect | SeoTrait | HasSeo |
|--------|----------|---------|
| Titel | `$seoTitle` (camelCase) | `$seo_title` (snake_case) |
| Beschrijving | `$seoDescription` (camelCase) | `$seo_description` (snake_case) |
| Keywords | ❌ Niet beschikbaar | `$seo_keywords` (snake_case) |

### Methode Namen
| Functie | SeoTrait | HasSeo |
|---------|----------|---------|
| Data ophalen | `getSeoLayoutData()` | `getSeoData()` |
| Titel setter | ❌ Niet beschikbaar | `setSeoTitle()` |
| Beschrijving setter | ❌ Niet beschikbaar | `setSeoDescription()` |

### Data Bron
- **SeoTrait**: Database-driven via `Routeseo` model
- **HasSeo**: Programmatisch ingesteld via setter methoden

### Layout Data Structuur

**SeoTrait output:**
```php
[
    'title' => $this->seoTitle,
    'seo_title' => $this->seoTitle,
    'seo_description' => $this->seoDescription,
    'routeseo' => $this->routeseo,
]
```

**HasSeo output:**
```php
[
    'seo_title' => $this->seo_title,
    'seoTitle' => $this->seo_title, // Alias voor compatibility
    'seo_description' => $this->seo_description,
    'seo_keywords' => $this->seo_keywords,
    'canonical_url' => $this->canonical_url,
    'robots' => $this->robots,
    'og_title' => $this->og_title,
    // ... veel meer properties
]
```

## 🎯 Aanbevelingen

### Optie 1: SeoTrait Harmoniseren (Aanbevolen)

Update SeoTrait om consistent te zijn met HasSeo:

```php
trait SeoTrait
{
    // Gebruik snake_case properties zoals HasSeo
    public $seo_title;
    public $seo_description;
    public $routeseo;

    public function loadSeoData()
    {
        // Bestaande logica behouden
        if ($this->routeseo) {
            $this->seo_title = $this->routeseo->seo_title ?: config('app.name');
            $this->seo_description = $this->routeseo->seo_description ?: '';
        }
    }

    public function getSeoData(): array // Consistente methode naam
    {
        return [
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'routeseo' => $this->routeseo,
        ];
    }
}
```

### Optie 2: Hybride Benadering

Maak SeoTrait compatible met beide naming conventions:

```php
trait SeoTrait
{
    public $seo_title;
    public $seo_description;
    public $routeseo;

    // Getter properties voor backward compatibility
    public function getSeoTitleAttribute()
    {
        return $this->seo_title;
    }

    public function getSeoDescriptionAttribute()
    {
        return $this->seo_description;
    }

    public function getSeoData(): array
    {
        return [
            'seo_title' => $this->seo_title,
            'seoTitle' => $this->seo_title, // Alias
            'seo_description' => $this->seo_description,
            'seoDescription' => $this->seo_description, // Alias
            'routeseo' => $this->routeseo,
        ];
    }
}
```

### Optie 3: Unified Trait

Creëer een nieuwe trait die beide functionaliteiten combineert:

```php
trait UnifiedSeoTrait
{
    use HasSeo;
    
    public $routeseo;

    public function loadSeoData()
    {
        // Database SEO data laden
        $routeName = Route::currentRouteName();
        $this->routeseo = Routeseo::where(['route' => $routeName, 'locale' => app()->getLocale()])->first();
        
        if ($this->routeseo) {
            // Database data overschrijft programmatic data
            $this->setSeoTitle($this->routeseo->seo_title ?: config('app.name'));
            $this->setSeoDescription($this->routeseo->seo_description ?: '');
        }
    }

    public function getSeoData(): array
    {
        return array_merge(parent::getSeoData(), [
            'routeseo' => $this->routeseo,
        ]);
    }
}
```

## 🔧 Implementatie Aanbeveling

**Stap 1: SeoTrait Updaten (Breaking Change)**
- Update property namen naar snake_case
- Hernoem `getSeoLayoutData()` naar `getSeoData()`
- Voeg aliases toe voor backward compatibility

**Stap 2: Layout Templates Updaten**
- Gebruik consistente property namen in alle layouts
- Standaardiseer op snake_case (`$seo_title`, `$seo_description`)

**Stap 3: Documentatie Updaten**
- Update alle documentatie met consistente naming
- Voeg migration guide toe voor bestaande code

## 🚨 Breaking Changes

Bij implementatie van Optie 1:
- `$seoTitle` → `$seo_title`
- `$seoDescription` → `$seo_description`
- `getSeoLayoutData()` → `getSeoData()`

## 💡 Best Practices

1. **Consistente Naming**: Gebruik snake_case voor alle SEO properties
2. **Unified Interface**: Beide traits moeten `getSeoData()` implementeren
3. **Backward Compatibility**: Voeg aliases toe tijdens transitie periode
4. **Documentation**: Update alle voorbeelden met nieuwe naming convention
5. **Testing**: Test alle bestaande implementaties na changes

## 🔄 Migration Path

Voor bestaande projecten:

```php
// Oud (SeoTrait)
$this->seoTitle
$this->getSeoLayoutData()

// Nieuw (Consistent)
$this->seo_title
$this->getSeoData()
```

Layout templates:
```blade
<!-- Oud -->
{{ $title ?? ($seoTitle ?? config('app.name')) }}

<!-- Nieuw -->
{{ $seo_title ?? ($title ?? config('app.name')) }}
```
