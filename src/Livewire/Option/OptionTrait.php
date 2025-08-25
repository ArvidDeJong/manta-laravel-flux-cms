<?php

namespace Manta\FluxCMS\Livewire\Option;

use Manta\FluxCMS\Services\ModuleSettingsService;

trait OptionTrait
{
    public function __construct()
    {
        $this->module_routes = [
            'name' => 'option',
            'list' => 'manta-cms.option.update',
            'create' => 'manta-cms.option.create',
            'update' => 'manta-cms.option.update',
            'read' => 'manta-cms.option.read',
            'upload' => 'manta-cms.option.upload',
            'settings' => 'manta-cms.option.settings',
            'maps' => null,
        ];

        $settings = ModuleSettingsService::ensureModuleSettings('option', 'darvis/manta-laravel-flux-cms');
        $this->config = $settings;

        $this->fields = $settings['fields'] ?? [];
        $this->tab_title = $settings['tab_title'] ?? null;
        $this->moduleClass = 'Manta\Models\Option';
    }
}
