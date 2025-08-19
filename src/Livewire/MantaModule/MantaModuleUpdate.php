<?php

namespace Manta\FluxCMS\Livewire\MantaModule;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Manta\FluxCMS\Models\MantaModule;
use Manta\FluxCMS\Traits\MantaTrait;

#[Layout('manta-cms::layouts.app')]
class MantaModuleUpdate extends Component
{
    use MantaTrait;
    use MantaModuleTrait;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'module_name' => 'nullable|array',
            'tabtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:module,webshop,tool,dev',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Naam is verplicht.',
            'name.string' => 'Naam moet een tekst zijn.',
            'name.max' => 'Naam mag maximaal 255 karakters bevatten.',

            'title.required' => 'Titel is verplicht.',
            'title.string' => 'Titel moet een tekst zijn.',
            'title.max' => 'Titel mag maximaal 255 karakters bevatten.',

            'module_name.array' => 'Module naam moet een array zijn.',

            'tabtitle.string' => 'Tabtitel moet een tekst zijn.',
            'tabtitle.max' => 'Tabtitel mag maximaal 255 karakters bevatten.',

            'description.string' => 'Beschrijving moet een tekst zijn.',
            'description.max' => 'Beschrijving mag maximaal 500 karakters bevatten.',

            'type.required' => 'Type is verplicht.',
            'type.in' => 'Type moet een van de volgende waarden zijn: module, webshop, tools, dev.',
        ];
    }

    public function mount(MantaModule $mantaModule)
    {
        $this->setItem($mantaModule);
    }

    public function render()
    {
        return view('manta-cms::livewire.manta-module.manta-module-update')->title($this->item->title . ' updaten');
    }

    public function save()
    {
        $this->validate();

        $row = $this->only(
            'company_id',
            'host',
            'locale',
            'active',
            'sort',
            'name',
            'title',
            'module_name',
            'tabtitle',
            'description',
            'route',
            'url',
            'icon',
            'type',
            'rights'
        );

        // Normaliseer en cast array data
        $row['data'] = (array)$this->data;
        $row['module_name'] = (array)$this->module_name;
        $row['fields'] = $this->normalizeFields(); // Gebruik de normalize methode
        $row['settings'] = (array)$this->settings;
        $row['ereg'] = (array)$this->ereg;

        // Cast andere data types
        $row['active'] = (bool)$this->active;
        $row['sort'] = (int)$this->sort;
        $row['company_id'] = (int)$this->company_id;

        $this->item->update($row);

        Flux::toast('Opgeslagen', duration: 1000, variant: 'success');
    }
}
