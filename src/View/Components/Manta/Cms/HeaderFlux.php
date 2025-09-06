<?php

namespace Manta\FluxCMS\View\Components\Manta\Cms;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;
use Manta\FluxCMS\Models\MantaNav;

class HeaderFlux extends Component
{
    public $generalModules;
    public $webshopModules;
    public $toolsModules;
    public $devModules;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Haal actieve navigatie items op uit de database
        $this->generalModules = MantaNav::active()
            ->byType('module')
            ->orderBy('sort')
            ->get();

        $this->webshopModules = MantaNav::active()
            ->byType('webshop')
            ->orderBy('sort')
            ->get();

        $this->toolsModules = Auth::user()->admin ? MantaNav::active()
            ->byType('tool')
            ->orderBy('sort')
            ->get() : collect();

        $this->devModules = Auth::user()->developer ? MantaNav::active()
            ->byType('dev')
            ->orderBy('sort')
            ->get() : collect();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('manta-cms::components.manta.cms.header-flux', [
            'generalModules' => $this->generalModules,
            'webshopModules' => $this->webshopModules,
            'toolsModules' => $this->toolsModules,
            'devModules' => $this->devModules,
        ]);
    }
}
