<?php

namespace Manta\FluxCMS\Livewire\Routeseo;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\Routeseo;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Services\SeoOptimizationService;

#[Layout('manta-cms::layouts.app')]
class RouteseoUpdate extends Component
{
    use MantaTrait;
    use RouteseoTrait;

    public function mount(Routeseo $routeseo)
    {
        $this->item = $routeseo;
        $this->itemOrg = $routeseo;
        $this->id = $routeseo->id;

        $this->data = is_array($routeseo->data) ? $routeseo->data : [];
        $this->fill(
            $routeseo->only(
                'pid',
                'locale',
                'title',
                'route',
                'seo_title',
                'seo_description',
            )
        );

        $this->getLocaleInfo();
        $this->getBreadcrumb('update');

        $this->openaiSubject = 'Optimaliseer de titel en beschrijving voor SEO';
    }

    public function render()
    {
        $this->openaiDescription = 'De titel is: ' . $this->title . ' De beschrijving is: ' . $this->seo_description;
        return view('manta-cms::livewire.default.manta-default-update');
    }

    public function save()
    {
        $this->validate();

        $row = $this->only(
            'pid',
            'locale',
            'title',
            'route',
            'seo_title',
            'seo_description',
            'data',
        );

        $this->item->update($row);

        Flux::toast('Route SEO opgeslagen', duration: 1000, variant: 'success');
    }
}
