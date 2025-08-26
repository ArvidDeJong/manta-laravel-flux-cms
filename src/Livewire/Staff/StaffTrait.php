<?php

namespace Manta\FluxCMS\Livewire\Staff;

use Manta\FluxCMS\Models\Staff;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Locked;
use Manta\FluxCMS\Models\MantaModule;
use Manta\FluxCMS\Services\ModuleSettingsService;

trait StaffTrait
{

    public function __construct()
    {
        $this->module_routes = [
            'name' => 'staff',
            'list' => 'manta-cms.staff.list',
            'create' => 'manta-cms.staff.create',
            'update' => 'manta-cms.staff.update',
            'read' => 'manta-cms.staff.read',
            'upload' => 'manta-cms.staff.upload',
            'settings' => 'manta-cms.staff.settings',
            'rights' => 'manta-cms.staff.rights',
            'maps' => null,
        ];

        $settings = ModuleSettingsService::ensureModuleSettings('staff', 'darvis/manta-laravel-flux-cms');
        $this->config = $settings;

        $this->fields = $settings['fields'] ?? [];
        $this->tab_title = $settings['tabtitle'] ?? null;

        $this->moduleClass = 'Manta\FluxCMS\Models\Staff';
    }

    public ?Staff $item = null;
    public ?Staff $itemOrg = null;


    public ?int $company_id = null;
    public ?string $host = null;
    public ?int $pid = null;
    public ?string $locale = null;
    public bool $active = true;
    public int $sort = 1;
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public bool $admin = false;
    public bool $developer = false;
    public ?string $comments = null;
    public ?string $password = null;


    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->where(function (Builder $querysub) {
                $querysub->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('email', 'LIKE', "%{$this->search}%");
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
                case 'name':
                    $rules[$field] = 'required|string|max:255';
                    break;
                case 'email':
                    $rules[$field] = 'required|string|email|max:255|' . $emailUniqueRule;
                    break;
                case 'password':
                    $rules[$field] = ['nullable', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'];
                    break;
                case 'phone':
                    $rules[$field] = 'required|string|regex:/^\+?[0-9]{12}$/';
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
