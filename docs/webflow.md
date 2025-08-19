# Webflow Componenten

Deze documentatie beschrijft de beschikbare Webflow-geïnspireerde componenten in het Manta Laravel Flux CMS package.

## Webflow Image Component

De `<x-manta.webflow-image>` component maakt het eenvoudig om responsive images te implementeren volgens de Webflow standaard werkwijze met automatische srcset generatie.

### Basis Gebruik

```blade
<x-manta.webflow-image 
    src="/vendor/theme-tuhogroep/images/tuho-header.jpg"
    alt="TUHO Header"
    class="hero_background-image"
/>
```

### Automatische Srcset Generatie

De component kan automatisch een srcset genereren op basis van een base path:

```blade
<x-manta.webflow-image 
    base-path="/vendor/theme-tuhogroep/images/tuho-header.jpg"
    alt="TUHO Header"
    class="hero_background-image"
/>
```

Dit genereert automatisch:
```html
<img 
    sizes="(max-width: 2000px) 100vw, 2000px"
    srcset="/vendor/theme-tuhogroep/images/tuho-header-p-500.jpg 500w, /vendor/theme-tuhogroep/images/tuho-header-p-800.jpg 800w, /vendor/theme-tuhogroep/images/tuho-header-p-1080.jpg 1080w, /vendor/theme-tuhogroep/images/tuho-header-p-1600.jpg 1600w, /vendor/theme-tuhogroep/images/tuho-header.jpg 2000w"
    alt="TUHO Header"
    src="/vendor/theme-tuhogroep/images/tuho-header.jpg"
    class="hero_background-image"
>
```

### Handmatige Srcset

Je kunt ook een handmatige srcset opgeven:

```blade
<x-manta.webflow-image 
    src="/vendor/theme-tuhogroep/images/tuho-header.jpg"
    srcset="/vendor/theme-tuhogroep/images/tuho-header-p-500.jpg 500w, /vendor/theme-tuhogroep/images/tuho-header-p-800.jpg 800w, /vendor/theme-tuhogroep/images/tuho-header.jpg 2000w"
    alt="TUHO Header"
    class="hero_background-image"
/>
```

### Custom Breakpoints

Pas de breakpoints aan voor verschillende responsive behoeften:

```blade
<x-manta.webflow-image 
    base-path="/images/product.jpg"
    :breakpoints="[400, 800, 1200]"
    sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
    alt="Product afbeelding"
/>
```

### Verschillende Bestandsformaten

Standaard wordt `.jpg` gebruikt, maar je kunt dit aanpassen:

```blade
<x-manta.webflow-image 
    base-path="/images/logo.png"
    extension="png"
    alt="Logo"
/>
```

## Component Parameters

| Parameter | Type | Default | Beschrijving |
|-----------|------|---------|--------------|
| `src` | string | '' | Directe bron URL voor de afbeelding |
| `alt` | string | '' | Alt tekst voor de afbeelding |
| `class` | string | '' | CSS classes voor de afbeelding |
| `sizes` | string | '(max-width: 2000px) 100vw, 2000px' | Sizes attribuut voor responsive images |
| `srcset` | string | '' | Handmatige srcset definitie |
| `breakpoints` | array | [500, 800, 1080, 1600, 2000] | Breakpoints voor automatische srcset generatie |
| `base-path` | string | '' | Base pad voor automatische srcset generatie |
| `extension` | string | 'jpg' | Bestandsextensie voor automatische srcset generatie |

## Webflow Naamgeving Conventie

De component volgt de Webflow naamgeving conventie:
- Originele afbeelding: `image.jpg`
- Responsive varianten: `image-p-500.jpg`, `image-p-800.jpg`, etc.

Waarbij `-p-{width}` de breedte in pixels aangeeft.

## Voorbeelden uit de Praktijk

### Hero Header Image

```blade
<header class="page-hero_bg-img">
    <x-manta.webflow-image 
        base-path="/vendor/theme-tuhogroep/images/tuho-header.jpg"
        alt="TUHO Groep Header"
        class="hero_background-image"
        data-slide="up"
    />
</header>
```

### Product Gallery

```blade
@foreach($products as $product)
    <div class="product-card">
        <x-manta.webflow-image 
            base-path="{{ $product->image_path }}"
            alt="{{ $product->name }}"
            class="product-image"
            :breakpoints="[300, 600, 900]"
            sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
        />
    </div>
@endforeach
```

### Thumbnail met Custom Sizes

```blade
<x-manta.webflow-image 
    base-path="/images/thumbnail.jpg"
    :breakpoints="[150, 300]"
    sizes="150px"
    alt="Thumbnail"
    class="thumbnail-image"
/>
```

## Extra Attributen

De component ondersteunt alle HTML attributen via Laravel's `$attributes` functionaliteit:

```blade
<!-- Webflow animaties -->
<x-manta.webflow-image 
    base-path="/images/hero.jpg"
    alt="Hero"
    class="hero-image"
    data-slide="up"
/>

<!-- Lazy loading -->
<x-manta.webflow-image 
    base-path="/images/product.jpg"
    alt="Product"
    loading="lazy"
/>

<!-- Custom ID en data attributen -->
<x-manta.webflow-image 
    base-path="/images/banner.jpg"
    alt="Banner"
    id="main-banner"
    data-aos="fade-in"
    data-aos-delay="200"
/>

<!-- Event handlers -->
<x-manta.webflow-image 
    base-path="/images/gallery.jpg"
    alt="Gallery"
    onclick="openModal()"
    style="cursor: pointer;"
/>
```

## Best Practices

1. **Gebruik base-path** voor automatische srcset generatie wanneer mogelijk
2. **Zorg voor correcte alt teksten** voor toegankelijkheid
3. **Pas breakpoints aan** op basis van je design behoeften
4. **Gebruik sizes attribuut** om de browser te helpen de juiste afbeelding te kiezen
5. **Test verschillende schermformaten** om te zorgen dat de juiste afbeeldingen worden geladen
6. **Voeg data-slide="up"** toe voor Webflow animaties
7. **Gebruik loading="lazy"** voor betere performance bij images onder de fold

## Integratie met Bestaande Webflow Templates

Deze component is ontworpen om naadloos te integreren met bestaande Webflow templates. Vervang gewoon de `<img>` tags met de `<x-manta.webflow-image>` component en profiteer van automatische responsive image handling.

### Conversie Voorbeeld

**Voor (originele Webflow HTML):**
```html
<img class="img-large" src="images/Compri-Aluminium---12.jpg" alt=""
     data-slide="up" sizes="(max-width: 767px) 100vw, (max-width: 991px) 728px, 800px"
     srcset="images/Compri-Aluminium---12-p-500.jpg 500w, images/Compri-Aluminium---12.jpg 800w">
```

**Na (Manta component):**
```blade
<x-manta.webflow-image 
    base-path="/vendor/theme-tuhogroep/images/Compri-Aluminium---12.jpg"
    alt=""
    class="img-large"
    data-slide="up"
    sizes="(max-width: 767px) 100vw, (max-width: 991px) 728px, 800px"
/>
```
