<?php

namespace Manta\FluxCMS\Traits;

trait HasSeo
{
    /**
     * SEO properties
     */
    public $seo_title;
    public $seo_description;
    public $seo_keywords;
    public $seo_author;
    public $canonical_url;
    public $robots;
    
    // Open Graph properties
    public $og_title;
    public $og_description;
    public $og_image;
    public $og_image_width;
    public $og_image_height;
    public $og_image_alt;
    public $og_type;
    public $og_url;
    
    // Twitter Card properties
    public $twitter_title;
    public $twitter_description;
    public $twitter_image;
    public $twitter_image_alt;
    public $twitter_card;
    
    // Schema.org properties
    public $schema_type;
    public $additional_schema;
    public $structured_data = true;
    
    // Additional properties
    public $theme_color;
    public $tile_color;

    /**
     * Set SEO title
     */
    public function setSeoTitle(string $title): self
    {
        $this->seo_title = $title;
        return $this;
    }

    /**
     * Set SEO description
     */
    public function setSeoDescription(string $description): self
    {
        $this->seo_description = $description;
        return $this;
    }

    /**
     * Set SEO keywords
     */
    public function setSeoKeywords(string $keywords): self
    {
        $this->seo_keywords = $keywords;
        return $this;
    }

    /**
     * Set canonical URL
     */
    public function setCanonicalUrl(string $url): self
    {
        $this->canonical_url = $url;
        return $this;
    }

    /**
     * Set robots meta
     */
    public function setRobots(string $robots): self
    {
        $this->robots = $robots;
        return $this;
    }

    /**
     * Set Open Graph data
     */
    public function setOpenGraph(array $data): self
    {
        foreach ($data as $key => $value) {
            $property = 'og_' . $key;
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
        return $this;
    }

    /**
     * Set Twitter Card data
     */
    public function setTwitterCard(array $data): self
    {
        foreach ($data as $key => $value) {
            $property = 'twitter_' . $key;
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
        return $this;
    }

    /**
     * Set Schema.org structured data
     */
    public function setStructuredData(string $type, array $additional = null): self
    {
        $this->schema_type = $type;
        if ($additional) {
            $this->additional_schema = json_encode($additional, JSON_UNESCAPED_SLASHES);
        }
        return $this;
    }

    /**
     * Disable structured data
     */
    public function disableStructuredData(): self
    {
        $this->structured_data = false;
        return $this;
    }

    /**
     * Set complete SEO data at once
     */
    public function setSeoData(array $data): self
    {
        // Basic SEO
        if (isset($data['title'])) $this->setSeoTitle($data['title']);
        if (isset($data['description'])) $this->setSeoDescription($data['description']);
        if (isset($data['keywords'])) $this->setSeoKeywords($data['keywords']);
        if (isset($data['canonical_url'])) $this->setCanonicalUrl($data['canonical_url']);
        if (isset($data['robots'])) $this->setRobots($data['robots']);

        // Open Graph
        if (isset($data['og'])) $this->setOpenGraph($data['og']);

        // Twitter Card
        if (isset($data['twitter'])) $this->setTwitterCard($data['twitter']);

        // Schema.org
        if (isset($data['schema_type'])) {
            $this->setStructuredData(
                $data['schema_type'], 
                $data['additional_schema'] ?? null
            );
        }

        // Colors
        if (isset($data['theme_color'])) $this->theme_color = $data['theme_color'];
        if (isset($data['tile_color'])) $this->tile_color = $data['tile_color'];

        return $this;
    }

    /**
     * Get page title with site name
     */
    public function getPageTitle(): string
    {
        $title = $this->seo_title ?? config('app.name');
        $siteName = config('app.name');
        $separator = ' - ';

        if ($title === config('app.name')) {
            return $title;
        }

        return $title . $separator . $siteName;
    }

    /**
     * Get all SEO data for layout
     */
    public function getSeoData(): array
    {
        return array_filter([
            'seo_title' => $this->seo_title,
            'seoTitle' => $this->seo_title, // Alias for camelCase compatibility
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
            'seo_author' => $this->seo_author,
            'canonical_url' => $this->canonical_url,
            'robots' => $this->robots,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $this->og_image,
            'og_image_width' => $this->og_image_width,
            'og_image_height' => $this->og_image_height,
            'og_image_alt' => $this->og_image_alt,
            'og_type' => $this->og_type,
            'og_url' => $this->og_url,
            'twitter_title' => $this->twitter_title,
            'twitter_description' => $this->twitter_description,
            'twitter_image' => $this->twitter_image,
            'twitter_image_alt' => $this->twitter_image_alt,
            'twitter_card' => $this->twitter_card,
            'schema_type' => $this->schema_type,
            'additional_schema' => $this->additional_schema,
            'structured_data' => $this->structured_data,
            'theme_color' => $this->theme_color,
            'tile_color' => $this->tile_color,
            'page_title' => $this->getPageTitle(),
        ], function($value) {
            return $value !== null;
        });
    }
}
