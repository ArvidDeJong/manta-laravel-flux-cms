<?php

namespace Manta\FluxCMS\View\Components;

use Illuminate\View\Component;

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
        string $extension = 'jpg'
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->class = $class;
        $this->sizes = $sizes;
        $this->srcset = $srcset;
        $this->breakpoints = $breakpoints;
        $this->basePath = $basePath;
        $this->extension = $extension;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('manta-flux-cms::components.webflow-image');
    }
}
