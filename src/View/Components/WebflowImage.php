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
        ?int $id = null
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

        if ($this->id) {
            $this->upload = Upload::find($this->id);
        }
        // Als we een Upload object hebben, genereer dan automatisch src en srcset
        if ($this->upload) {
            $this->src = $this->upload->getImage()['src'] ?? $this->fallbackSrc ?? '';
            $this->srcset = $this->generateSrcset();
        } else {
            $this->src = $src ?: $this->fallbackSrc ?? '';
            $this->srcset = $srcset;
        }
    }

    /**
     * Genereer automatisch srcset op basis van breakpoints
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
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('manta-cms::components.webflow-image');
    }
}
