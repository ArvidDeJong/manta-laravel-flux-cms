<?php

namespace Manta\FluxCMS\Traits;

use Manta\FluxCMS\Models\Routeseo;
use Illuminate\Support\Facades\Route;

trait SeoTrait
{
    public $seo_title;
    public $seo_description;
    public $routeseo;

    /**
     * Laad SEO data voor de huidige route
     */
    public function loadSeoData()
    {
        $routeName = Route::currentRouteName();

        // Zoek eerst naar SEO data voor huidige locale
        $this->routeseo = Routeseo::where(['route' => $routeName, 'locale' => app()->getLocale()])->first();

        // Als niet gevonden, zoek naar default locale
        if (!$this->routeseo) {
            $this->routeseo = Routeseo::where(['route' => $routeName, 'locale' => config('app.locale', 'nl')])->first();
        }

        // Stel SEO waarden in (database waarden hebben voorrang)
        if ($this->routeseo) {
            // Database waarden overschrijven altijd de hardcoded waarden (als ze gevuld zijn)
            if (!empty($this->routeseo->seo_title)) {
                $this->seo_title = $this->routeseo->seo_title;
            } elseif (empty($this->seo_title)) {
                $this->seo_title = config('app.name');
            }

            if (!empty($this->routeseo->seo_description)) {
                $this->seo_description = $this->routeseo->seo_description;
            } elseif (empty($this->seo_description)) {
                $this->seo_description = '';
            }
        } else {

            // Maak automatisch een SEO record aan als het niet bestaat
            $this->routeseo = Routeseo::create([
                'route' => $routeName,
                'locale' => config('app.locale', 'nl'),
                'seo_title' => $this->seo_title ?: config('app.name'),
                'seo_description' => $this->seo_description ?: '',
            ]);

            // Stel alleen fallback waarden in als er nog geen waarden zijn
            if (empty($this->seo_title)) {
                $this->seo_title = config('app.name');
            }
            if (empty($this->seo_description)) {
                $this->seo_description = '';
            }
        }
    }

    /**
     * Geef SEO data door aan de layout
     */
    public function getSeoData(): array
    {
        return [
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'routeseo' => $this->routeseo,
            // Backward compatibility aliases
            'title' => $this->seo_title,
            'seoTitle' => $this->seo_title,
            'seoDescription' => $this->seo_description,
        ];
    }

    /**
     * @deprecated Use getSeoData() instead
     */
    public function getSeoLayoutData(): array
    {
        return $this->getSeoData();
    }

    /**
     * Get SEO title
     */
    public function getSeoTitle(): string
    {
        return $this->seo_title ?? config('app.name');
    }

    /**
     * Get SEO description
     */
    public function getSeoDescription(): string
    {
        return $this->seo_description ?? '';
    }
}
