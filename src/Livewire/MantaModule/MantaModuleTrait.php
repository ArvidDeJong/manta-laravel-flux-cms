<?php

namespace Manta\FluxCMS\Livewire\MantaModule;

use Manta\FluxCMS\Models\MantaModule;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Locked;

trait MantaModuleTrait
{

    public function __construct() {}

    public ?MantaModule $item = null;
    public ?MantaModule $itemOrg = null;

    public ?int $company_id = null;
    public ?string $host = null;
    public ?string $locale = null;
    public bool $active = true;
    public int $sort = 1;
    public ?string $name = null;
    public ?string $title = null;
    public ?string $tabtitle = null;
    public ?string $description = null;
    public ?string $route = null;
    public ?string $url = null;
    public ?string $icon = null;
    public ?string $type = null;
    public ?array $rights = null;

    public array $data = [];
    public array $module_name = [];
    public array $fields = [];
    public array $settings = [];
    public array $ereg = [];

    public function setItem(MantaModule $mantaModule)
    {
        $this->item = $mantaModule;
        $this->id = $mantaModule->id;
        // Vul de properties voor readonly weergave
        $this->fill(
            $mantaModule->only(
                'company_id',
                'host',
                'locale',
                'name',
                'title',
                'tabtitle',
                'description',
                'route',
                'url',
                'icon',
                'type',
                'rights',
            )
        );


        $this->data = is_array($mantaModule->data) ? $mantaModule->data : [];
        $this->module_name = (array)$mantaModule->module_name;
        $this->fields = (array)$mantaModule->fields;
        $this->settings = (array)$mantaModule->settings;
        $this->ereg = (array)$mantaModule->ereg;
    }

    /**
     * Normaliseert de fields array met standaardwaarden
     */
    protected function normalizeFields(): array
    {
        $defaultFieldStructure = [
            'active' => true,
            'type' => 'text',
            'title' => '',
            'read' => true,
            'read_type' => 'string',
            'required' => false,
            'edit' => true,
            'options' => [],
            'default' => null,
            'step' => 1,
            'min' => 1,
            'max' => 1,
        ];

        $normalizedFields = [];

        foreach ($this->fields as $key => $field) {
            // Zorg ervoor dat $field een array is
            if (!is_array($field)) {
                $field = [];
            }

            // Merge met standaardwaarden
            $normalizedFields[$key] = array_merge($defaultFieldStructure, $field);

            // Zorg voor correcte data types
            $normalizedFields[$key]['active'] = (bool)($normalizedFields[$key]['active'] ?? true);
            $normalizedFields[$key]['read'] = (bool)($normalizedFields[$key]['read'] ?? true);
            $normalizedFields[$key]['required'] = (bool)($normalizedFields[$key]['required'] ?? false);
            $normalizedFields[$key]['edit'] = (bool)($normalizedFields[$key]['edit'] ?? true);
            $normalizedFields[$key]['title'] = (string)($normalizedFields[$key]['title'] ?? '');
            $normalizedFields[$key]['type'] = (string)($normalizedFields[$key]['type'] ?? 'text');
            $normalizedFields[$key]['read_type'] = (string)($normalizedFields[$key]['read_type'] ?? 'string');
            $normalizedFields[$key]['options'] = (array)($normalizedFields[$key]['options'] ?? []);
            $normalizedFields[$key]['default'] = (string)($normalizedFields[$key]['default'] ?? null);
            $normalizedFields[$key]['step'] = (int)($normalizedFields[$key]['step'] ?? 1);
            $normalizedFields[$key]['min'] = (int)($normalizedFields[$key]['min'] ?? 1);
            $normalizedFields[$key]['max'] = (int)($normalizedFields[$key]['max'] ?? 1);
        }

        // Zorg ervoor dat uploads en maps keys aanwezig zijn
        if (!isset($normalizedFields['uploads'])) {
            $normalizedFields['uploads'] = array_merge($defaultFieldStructure, [
                'title' => 'Uploads',
                'type' => '',
                'read' => false,
                'edit' => false,
            ]);
        }

        if (!isset($normalizedFields['maps'])) {
            $normalizedFields['maps'] = array_merge($defaultFieldStructure, [
                'title' => 'Google Maps',
                'type' => '',
                'read' => false,
                'edit' => false,
            ]);
        }

        return $normalizedFields;
    }


    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->where(function (Builder $querysub) {
                $querysub->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('title', 'LIKE', "%{$this->search}%")
                    ->orWhere('description', 'LIKE', "%{$this->search}%")
                    ->orWhere('type', 'LIKE', "%{$this->search}%");
            });
    }
}
