<?php

namespace Manta\FluxCMS\Livewire\MantaNav;

use Manta\FluxCMS\Models\MantaNav;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Locked;

trait MantaNavTrait
{

    public function __construct()
    {
        $this->route_name = 'manta-nav';
        $this->route_list = route('manta-cms.' . $this->route_name . '.list');
        $this->config = module_config('manta-nav');
        $this->fields = []; //$this->config['fields'];
        $this->tab_title = isset($this->config['tab_title']) ? $this->config['tab_title'] : null;
        $this->moduleClass = 'Manta\FluxCMS\Models\MantaNav';
    }

    public ?MantaNav $item = null;
    public ?MantaNav $itemOrg = null;

    public ?int $company_id = null;
    public ?string $host = null;
    public ?int $pid = null;
    public ?string $locale = null;
    public bool $active = true;
    public int $sort = 1;
    public string $title = '';
    public ?string $route = null;
    public ?string $url = null;
    public ?string $type = null;

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->where(function (Builder $querysub) {
                $querysub->where('title', 'LIKE', "%{$this->search}%")
                    ->orWhere('route', 'LIKE', "%{$this->search}%");
            });
    }

    public function rules()
    {
        $itemId = $this->item ? $this->item->id : null;
        $emailUniqueRule = $itemId ? 'unique:users,email,' . $itemId : 'unique:users,email';

        $rules = [];

        foreach ($this->fields as $field => $properties) {
            if (!$properties['required']) {
                continue;
            }

            switch ($field) {
                case 'title':
                    $rules[$field] = 'required|string|max:255';
                    break;
                case 'route':
                    $rules[$field] = 'required|string|max:255';
                    break;
                case 'url':
                    $rules[$field] = 'required|string|max:255';
                    break;
                case 'type':
                    $rules[$field] = 'required|string|max:255';
                    break;
                case 'locale':
                    $rules[$field] = 'required|string|max:255';
                    break;
                case 'active':
                case 'admin':
                case 'developer':
                    $rules[$field] = 'boolean';
                    break;
                case 'comments':
                    $rules[$field] = 'nullable|string';
                    break;
                    // Voeg hier eventueel nog andere velden toe als dat nodig is.
                default:
                    $rules[$field] = 'nullable';
                    break;
            }
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'name.required' => 'Naam is vereist.',
            'email.required' => 'E-mailadres is vereist.',
            'email.email' => 'Voer een geldig e-mailadres in.',
            'email.unique' => 'Dit e-mailadres is al in gebruik.',
            'password.required' => 'Wachtwoord is vereist.',
            'password.min' => 'Het wachtwoord moet minstens 8 tekens lang zijn.',
            'password.regex' => 'Het wachtwoord moet minimaal één kleine letter, één hoofdletter, één cijfer en één speciaal teken bevatten.',
            'active.boolean' => 'De waarde van actief moet een geldige boolean zijn.',
            'admin.boolean' => 'De waarde van admin moet een geldige boolean zijn.',
            'developer.boolean' => 'De waarde van developer moet een geldige boolean zijn.',
            'comments.string' => 'Opmerkingen moeten een geldige tekst zijn.',
            'comments.nullable' => 'Opmerkingen mogen leeg zijn.',
            'phone.required' => 'Telefoonnummer is vereist.',
            'phone.regex' => 'Het telefoonnummer moet exact 12 cijfers bevatten en mag optioneel beginnen met een "+".',
            'phone.string' => 'Het telefoonnummer moet een geldige nummer zijn',
        ];
    }
}
