<?php

namespace Manta\FluxCMS\Livewire\Routeseo;

use Flux\Flux;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Manta\FluxCMS\Models\Routeseo;
use Manta\FluxCMS\Services\MantaOpenai;
use Manta\FluxCMS\Services\ModuleSettingsService;
use Manta\FluxCMS\Services\SeoOptimizationService;

trait RouteseoTrait
{

    public function __construct()
    {
        $this->module_routes = [
            'name' => 'routeseo',
            'list' => 'manta-cms.routeseo.list',
            'create' => 'manta-cms.routeseo.create',
            'update' => 'manta-cms.routeseo.update',
            'read' => 'manta-cms.routeseo.read',
            'upload' => 'manta-cms.routeseo.upload',
            'settings' => 'manta-cms.routeseo.settings',
            'rights' => 'manta-cms.routeseo.rights',
            'maps' => null,
        ];

        $settings = ModuleSettingsService::ensureModuleSettings('routeseo', 'darvis/manta-laravel-flux-cms');
        $this->config = $settings;

        $this->fields = $settings['fields'] ?? [];
        $this->tab_title = $settings['tabtitle'] ?? null;

        $this->moduleClass = 'Manta\FluxCMS\Models\Routeseo';
    }

    public ?Routeseo $item = null;
    public ?Routeseo $itemOrg = null;

    public ?string $pid = null;
    public ?string $locale = null;
    public ?string $title = null;
    public ?string $route = null;
    public ?string $seo_title = null;
    public ?string $seo_description = null;

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->where(function (Builder $querysub) {
                $querysub->where('seo_title', 'LIKE', "%{$this->search}%")
                    ->orWhere('seo_description', 'LIKE', "%{$this->search}%")
                    ->orWhere('title', 'LIKE', "%{$this->search}%");
            });
    }

    public function rules()
    {
        $return = [];

        if ($this->fields['locale']['active'] == true && $this->fields['locale']['required'] == true) {
            $return['locale'] = 'nullable|string|max:255';
        }

        if ($this->fields['title']['active'] == true && $this->fields['title']['required'] == true) {
            $return['title'] = 'nullable|string|max:255';
        }

        if ($this->fields['route']['active'] == true && $this->fields['route']['required'] == true) {
            $return['route'] = 'nullable|string|max:255';
        }

        if ($this->fields['seo_title']['active'] == true && $this->fields['seo_title']['required'] == true) {
            $return['seo_title'] = 'nullable|string|max:255';
        }

        if ($this->fields['seo_description']['active'] == true && $this->fields['seo_description']['required'] == true) {
            $return['seo_description'] = 'nullable|string|max:255';
        }

        return $return;
    }

    public function messages()
    {
        return [
            'locale.required' => 'De taal is verplicht',
            'title.required' => 'De titel is verplicht',
            'route.required' => 'De route is verplicht',
            'seo_title.required' => 'De SEO-titel is verplicht',
            'seo_description.required' => 'De SEO-beschrijving is verplicht',
        ];
    }

    public function getOpenaiResult()
    {
        Flux::modals()->close();

        $ai = app(MantaOpenai::class);

        $result = $ai->generate(
            $this->openaiSubject . ' ' . $this->openaiDescription,
            [
                'title' => 'Korte titel',
                'seo_title' => 'SEO titel',
                'seo_description' => 'SEO beschrijving',
            ]
        );

        $this->title = $result['title'];
        $this->seo_title = $result['seo_title'];
        $this->seo_description = $result['seo_description'];
    }
}
