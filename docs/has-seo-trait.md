# HasSeo Trait Documentatie

De `HasSeo` trait biedt uitgebreide SEO functionaliteit voor Livewire componenten, inclusief meta tags, Open Graph, Twitter Cards en Schema.org structured data.

## Installatie

De HasSeo trait is onderdeel van het `darvis/manta-laravel-flux-cms` package en wordt automatisch geladen.

## Basis Gebruik

### 1. Trait toevoegen aan component

```php
<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Manta\FluxCMS\Traits\HasSeo;

class HomePage extends Component
{
    use HasSeo;
    
    public function mount()
    {
        $this->setSeoTitle('Welkom bij TUHO Groep')
             ->setSeoDescription('Ontdek onze innovatieve oplossingen voor bedrijfsgroei')
             ->setSeoKeywords('TUHO Groep, homepage, bedrijfsgroei');
    }
    
    public function render()
    {
        return view('livewire.pages.home')
            ->layoutData($this->getSeoData());
    }
}
```

## Beschikbare Properties

De trait voegt de volgende properties toe aan je component:

### Basic SEO
- `$seo_title` - SEO titel
- `$seo_description` - SEO beschrijving
- `$seo_keywords` - SEO keywords
- `$seo_author` - Auteur
- `$canonical_url` - Canonical URL
- `$robots` - Robots meta tag

### Open Graph
- `$og_title` - Open Graph titel
- `$og_description` - Open Graph beschrijving
- `$og_image` - Open Graph afbeelding URL
- `$og_image_width` - Afbeelding breedte
- `$og_image_height` - Afbeelding hoogte
- `$og_image_alt` - Afbeelding alt tekst
- `$og_type` - Open Graph type
- `$og_url` - Open Graph URL

### Twitter Cards
- `$twitter_title` - Twitter titel
- `$twitter_description` - Twitter beschrijving
- `$twitter_image` - Twitter afbeelding
- `$twitter_image_alt` - Twitter afbeelding alt tekst
- `$twitter_card` - Twitter card type

### Schema.org
- `$schema_type` - Schema.org type
- `$additional_schema` - Extra schema data
- `$structured_data` - Structured data aan/uit

### Styling
- `$theme_color` - Theme kleur
- `$tile_color` - Tile kleur

## Beschikbare Methoden

### Basic SEO Methoden

```php
// SEO titel instellen
$this->setSeoTitle('Mijn Pagina Titel');

// SEO beschrijving instellen
$this->setSeoDescription('Een korte beschrijving van de pagina');

// SEO keywords instellen
$this->setSeoKeywords('keyword1, keyword2, keyword3');

// Canonical URL instellen
$this->setCanonicalUrl('https://example.com/canonical-url');

// Robots meta instellen
$this->setRobots('index, follow');
```

### Open Graph Methoden

```php
// Open Graph data instellen
$this->setOpenGraph([
    'title' => 'OG Titel',
    'description' => 'OG Beschrijving',
    'image' => 'https://example.com/image.jpg',
    'image_width' => 1200,
    'image_height' => 630,
    'image_alt' => 'Afbeelding beschrijving',
    'type' => 'website',
    'url' => 'https://example.com'
]);
```

### Twitter Card Methoden

```php
// Twitter Card data instellen
$this->setTwitterCard([
    'title' => 'Twitter Titel',
    'description' => 'Twitter Beschrijving',
    'image' => 'https://example.com/twitter-image.jpg',
    'image_alt' => 'Twitter afbeelding beschrijving',
    'card' => 'summary_large_image'
]);
```

### Schema.org Methoden

```php
// Schema.org structured data instellen
$this->setStructuredData('Organization', [
    'name' => 'TUHO Groep',
    'url' => 'https://tuhogroep.nl',
    'logo' => 'https://tuhogroep.nl/logo.png'
]);

// Structured data uitschakelen
$this->disableStructuredData();
```

### Alles-in-één Methode

```php
// Alle SEO data in één keer instellen
$this->setSeoData([
    'title' => 'Pagina Titel',
    'description' => 'Pagina beschrijving',
    'keywords' => 'keyword1, keyword2',
    'canonical_url' => 'https://example.com/canonical',
    'robots' => 'index, follow',
    'og' => [
        'title' => 'OG Titel',
        'description' => 'OG Beschrijving',
        'image' => 'https://example.com/og-image.jpg',
        'type' => 'website'
    ],
    'twitter' => [
        'title' => 'Twitter Titel',
        'description' => 'Twitter Beschrijving',
        'image' => 'https://example.com/twitter-image.jpg',
        'card' => 'summary_large_image'
    ],
    'schema_type' => 'WebPage',
    'theme_color' => '#1a365d',
    'tile_color' => '#1a365d'
]);
```

### Helper Methoden

```php
// Volledige pagina titel met site naam
$pageTitle = $this->getPageTitle();

// Alle SEO data voor layout
$seoData = $this->getSeoData();
```

## Layout Integratie

In je layout template kun je alle SEO data gebruiken:

```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Basic SEO -->
    <title>{{ $page_title ?? $seo_title ?? config('app.name') }}</title>
    
    @if($seo_description)
        <meta name="description" content="{{ $seo_description }}">
    @endif
    
    @if($seo_keywords)
        <meta name="keywords" content="{{ $seo_keywords }}">
    @endif
    
    @if($seo_author)
        <meta name="author" content="{{ $seo_author }}">
    @endif
    
    @if($canonical_url)
        <link rel="canonical" href="{{ $canonical_url }}">
    @endif
    
    @if($robots)
        <meta name="robots" content="{{ $robots }}">
    @endif
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $og_title ?? $seo_title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $og_description ?? $seo_description }}">
    <meta property="og:type" content="{{ $og_type ?? 'website' }}">
    <meta property="og:url" content="{{ $og_url ?? request()->url() }}">
    
    @if($og_image)
        <meta property="og:image" content="{{ $og_image }}">
        @if($og_image_width)
            <meta property="og:image:width" content="{{ $og_image_width }}">
        @endif
        @if($og_image_height)
            <meta property="og:image:height" content="{{ $og_image_height }}">
        @endif
        @if($og_image_alt)
            <meta property="og:image:alt" content="{{ $og_image_alt }}">
        @endif
    @endif
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $twitter_card ?? 'summary' }}">
    <meta name="twitter:title" content="{{ $twitter_title ?? $seo_title ?? config('app.name') }}">
    <meta name="twitter:description" content="{{ $twitter_description ?? $seo_description }}">
    
    @if($twitter_image)
        <meta name="twitter:image" content="{{ $twitter_image }}">
        @if($twitter_image_alt)
            <meta name="twitter:image:alt" content="{{ $twitter_image_alt }}">
        @endif
    @endif
    
    <!-- Theme Colors -->
    @if($theme_color)
        <meta name="theme-color" content="{{ $theme_color }}">
    @endif
    
    @if($tile_color)
        <meta name="msapplication-TileColor" content="{{ $tile_color }}">
    @endif
    
    <!-- Schema.org Structured Data -->
    @if($structured_data && $schema_type)
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "{{ $schema_type }}",
            "name": "{{ $seo_title ?? config('app.name') }}",
            "description": "{{ $seo_description }}",
            "url": "{{ request()->url() }}"
            @if($additional_schema)
                ,{!! $additional_schema !!}
            @endif
        }
        </script>
    @endif
</head>
<body>
    {{ $slot }}
</body>
</html>
```

## Voorbeelden

### Homepage met volledige SEO

```php
use Manta\FluxCMS\Traits\HasSeo;

class HomePage extends Component
{
    use HasSeo;
    
    public function mount()
    {
        $this->setSeoData([
            'title' => 'TUHO Groep - Innovatieve Bedrijfsoplossingen',
            'description' => 'Ontdek hoe TUHO Groep uw bedrijf kan helpen groeien met onze innovatieve oplossingen en expertise.',
            'keywords' => 'TUHO Groep, bedrijfsoplossingen, innovatie, groei',
            'canonical_url' => route('website.home'),
            'robots' => 'index, follow',
            'og' => [
                'title' => 'TUHO Groep - Innovatieve Bedrijfsoplossingen',
                'description' => 'Ontdek hoe TUHO Groep uw bedrijf kan helpen groeien.',
                'image' => asset('images/og-homepage.jpg'),
                'image_width' => 1200,
                'image_height' => 630,
                'type' => 'website',
                'url' => route('website.home')
            ],
            'twitter' => [
                'title' => 'TUHO Groep - Innovatieve Bedrijfsoplossingen',
                'description' => 'Ontdek hoe TUHO Groep uw bedrijf kan helpen groeien.',
                'image' => asset('images/twitter-homepage.jpg'),
                'card' => 'summary_large_image'
            ],
            'schema_type' => 'Organization',
            'additional_schema' => json_encode([
                'name' => 'TUHO Groep',
                'url' => 'https://tuhogroep.nl',
                'logo' => asset('images/logo.png'),
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+31-123-456789',
                    'contactType' => 'customer service'
                ]
            ], JSON_UNESCAPED_SLASHES),
            'theme_color' => '#1a365d',
            'tile_color' => '#1a365d'
        ]);
    }
}
```

### Nieuwsartikel met dynamische SEO

```php
class NewsArticle extends Component
{
    use HasSeo;
    
    public $article;
    
    public function mount($slug)
    {
        $this->article = News::where('slug', $slug)->firstOrFail();
        
        $this->setSeoData([
            'title' => $this->article->title,
            'description' => $this->article->excerpt,
            'keywords' => $this->article->tags,
            'canonical_url' => route('website.news.article', $slug),
            'robots' => 'index, follow',
            'og' => [
                'title' => $this->article->title,
                'description' => $this->article->excerpt,
                'image' => $this->article->featured_image,
                'type' => 'article',
                'url' => route('website.news.article', $slug)
            ],
            'twitter' => [
                'title' => $this->article->title,
                'description' => $this->article->excerpt,
                'image' => $this->article->featured_image,
                'card' => 'summary_large_image'
            ],
            'schema_type' => 'NewsArticle',
            'additional_schema' => json_encode([
                'headline' => $this->article->title,
                'datePublished' => $this->article->published_at->toISOString(),
                'dateModified' => $this->article->updated_at->toISOString(),
                'author' => [
                    '@type' => 'Person',
                    'name' => $this->article->author
                ]
            ], JSON_UNESCAPED_SLASHES)
        ]);
    }
}
```

## Combinatie met SeoTrait

Je kunt HasSeo combineren met SeoTrait voor database-driven SEO:

```php
use Manta\FluxCMS\Traits\HasSeo;
use Manta\FluxCMS\Traits\SeoTrait;

class ContactPage extends Component
{
    use HasSeo, SeoTrait;
    
    public function mount()
    {
        // Laad database SEO data
        $this->loadSeoData();
        
        // Override met HasSeo voor extra functionaliteit
        $this->setSeoData([
            'title' => $this->seoTitle,
            'description' => $this->seoDescription,
            'og' => [
                'title' => $this->seoTitle,
                'description' => $this->seoDescription,
                'type' => 'website'
            ],
            'schema_type' => 'ContactPage'
        ]);
    }
    
    public function render()
    {
        return view('livewire.pages.contact')
            ->layoutData(array_merge(
                $this->getSeoLayoutData(), // Van SeoTrait
                $this->getSeoData()        // Van HasSeo
            ));
    }
}
```

## Best Practices

1. **Consistente titels**: Gebruik een consistente titel structuur
2. **Optimale lengtes**: 
   - Titel: 50-60 karakters
   - Beschrijving: 150-160 karakters
3. **Unieke content**: Elke pagina moet unieke SEO data hebben
4. **Image optimalisatie**: Gebruik 1200x630 voor Open Graph images
5. **Schema.org**: Voeg relevante structured data toe voor betere SEO
6. **Mobile-first**: Denk aan mobile gebruikers bij SEO optimalisatie
