<?php

namespace Manta\FluxCMS\View\Components;

use Illuminate\View\Component;
use Manta\FluxCMS\Models\Upload;

class WebflowImage extends Component
{
    public string $src;
    public string $alt;
    public string $class;
    public string $sizes;
    public string $srcset;
    public array $breakpoints;
    public string $basePath;
    public string $extension;
    public ?Upload $upload;
    public ?string $fallbackSrc;
    public ?int $id;
    public bool $showedit;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $src = '',
        string $alt = '',
        string $class = '',
        string $sizes = '(max-width: 2000px) 100vw, 2000px',
        string $srcset = '',
        array $breakpoints = [500, 800, 1080, 1600, 2000],
        string $basePath = '',
        string $extension = 'jpg',
        ?Upload $upload = null,
        ?string $fallbackSrc = null,
        ?int $id = null,
        bool $showedit = true
    ) {
        // Initialiseer eerst alle properties
        $this->alt = $alt;
        $this->class = $class;
        $this->sizes = $sizes;
        $this->breakpoints = $breakpoints;
        $this->basePath = $basePath;
        $this->extension = $extension;
        $this->upload = $upload;
        $this->fallbackSrc = $fallbackSrc;
        $this->id = $id;
        $this->showedit = $showedit;

        if ($this->id) {
            $this->upload = Upload::find($this->id);
        }
        // Als we een Upload object hebben, genereer dan automatisch src en srcset
        if ($this->upload) {
            $this->src = $this->upload->getImage()['src'] ?? $this->fallbackSrc ?? '';
            $this->srcset = $this->generateSrcset();
        } else {
            // Gebruik fallbackSrc als src leeg is en fallbackSrc beschikbaar is
            $this->src = $src ?: ($this->fallbackSrc ?? '');
            $this->srcset = $srcset;
            
            // Auto-generate srcset if not provided but basePath is given
            if (empty($this->srcset) && !empty($this->basePath)) {
                $this->srcset = $this->generateWebflowSrcset();
            }
        }
    }

    /**
     * Genereer automatisch srcset op basis van breakpoints voor Upload objecten
     */
    private function generateSrcset(): string
    {
        if (!$this->upload) {
            return '';
        }

        $srcsetParts = [];

        foreach ($this->breakpoints as $width) {
            $imageData = $this->upload->getImage($width);
            if (isset($imageData['src'])) {
                $srcsetParts[] = $imageData['src'] . ' ' . $width . 'w';
            }
        }

        return implode(', ', $srcsetParts);
    }

    /**
     * Genereer automatisch srcset op basis van basePath (Webflow conventie)
     */
    private function generateWebflowSrcset(): string
    {
        if (empty($this->basePath)) {
            return '';
        }

        $srcsetParts = [];
        $basePathWithoutExt = pathinfo($this->basePath, PATHINFO_DIRNAME) . '/' . pathinfo($this->basePath, PATHINFO_FILENAME);

        foreach ($this->breakpoints as $width) {
            if ($width === max($this->breakpoints)) {
                // Largest size uses original filename
                $srcsetParts[] = "{$this->basePath} {$width}w";
            } else {
                // Smaller sizes use -p-{width} suffix (Webflow convention)
                $srcsetParts[] = "{$basePathWithoutExt}-p-{$width}.{$this->extension} {$width}w";
            }
        }

        return implode(', ', $srcsetParts);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('manta-cms::components.webflow-image');
    }
}
