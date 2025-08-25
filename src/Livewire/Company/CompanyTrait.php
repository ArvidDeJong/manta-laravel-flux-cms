<?php

namespace Manta\FluxCMS\Livewire\Company;

use Manta\FluxCMS\Models\Company;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Locked;
use Manta\FluxCMS\Models\MantaModule;
use Manta\FluxCMS\Services\ModuleSettingsService;

trait CompanyTrait
{

    public function __construct()
    {
        $this->module_routes = [
            'name' => 'company',
            'list' => 'manta-cms.company.list',
            'create' => 'manta-cms.company.create',
            'update' => 'manta-cms.company.update',
            'read' => 'manta-cms.company.read',
            'upload' => 'manta-cms.company.upload',
            'settings' => 'manta-cms.company.settings',
            'maps' => null,
        ];

        $settings = ModuleSettingsService::ensureModuleSettings('company', 'darvis/manta-laravel-flux-cms');
        $this->config = $settings;

        $this->fields = $settings['fields'] ?? [];
        $this->tab_title = $settings['tab_title'] ?? null;
        $this->moduleClass = 'Manta\FluxCMS\Models\Company';
    }

    public ?Company $item = null;
    public ?Company $itemOrg = null;

    public ?int $company_id = null;
    public ?string $host = null;
    public ?int $pid = null;
    public ?string $locale = null;
    public bool $active = true;
    public int $sort = 1;
    public ?string $administration = null;
    public ?string $identifier = null;
    public ?string $relation_nr = null;
    public ?string $debtor_nr = null;
    public ?string $user_nr = null;
    public ?string $number = null;
    public ?string $sex = null;
    public ?string $initials = null;
    public ?string $lastname = null;
    public ?string $firstnames = null;
    public ?string $nameInsertion = null;
    public ?string $company = null;
    public ?string $companyNr = null;
    public ?string $taxNr = null;
    public ?string $address = null;
    public ?string $housenumber = null;
    public ?string $addressSuffix = null;
    public ?string $zipcode = null;
    public ?string $city = null;
    public ?string $country = 'nl';
    public ?string $state = null;
    public ?string $birthdate = null;
    public ?string $birthcity = null;
    public ?string $phone = null;
    public ?string $phone2 = null;
    public ?string $bsn = null;
    public ?string $iban = null;
    public ?string $google_maps_embed = null;
    public ?float $latitude = null;
    public ?float $longitude = null;


    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->where(function (Builder $querysub) {
                $querysub->where('company', 'LIKE', "%{$this->search}%")
                    ->orWhere('lastname', 'LIKE', "%{$this->search}%")
                    ->orWhere('firstnames', 'LIKE', "%{$this->search}%")
                    ->orWhere('number', 'LIKE', "%{$this->search}%")
                    ->orWhere('city', 'LIKE', "%{$this->search}%");
            });
    }


    /**
     * Validatie regels voor het aanmaken van een bedrijf.
     *
     * @return array
     */
    public function rules(): array
    {
        $return['company'] =  'required|string|max:255';

        // Validatie voor 'title'
        if (isset($this->fields['firstnames']) && $this->fields['firstnames']['active'] == true && $this->fields['firstnames']['required'] == true) {
            $return['firstnames'] =  'required|string|max:255';
        }


        return $return;
    }


    public function messages()
    {
        return [
            'company.required' => 'Bedrijfsnaam is vereist.',
            'lastname.required' => 'Achternaam is vereist.',
            'firstnames.required' => 'Voornaam is vereist.',
            'number.unique' => 'Dit nummer is al in gebruik.',
            'phone.string' => 'Telefoonnummer moet een geldige tekst zijn.',
            'phone2.string' => 'Tweede telefoonnummer moet een geldige tekst zijn.',
            'zipcode.string' => 'Postcode moet een geldige tekst zijn.',
            'city.string' => 'Plaats moet een geldige tekst zijn.',
            'country.string' => 'Land moet een geldige tekst zijn.',
            'state.string' => 'Provincie moet een geldige tekst zijn.',
            'address.string' => 'Adres moet een geldige tekst zijn.',
            'latitude.numeric' => 'Latitude moet een geldig getal zijn.',
            'latitude.between' => 'Latitude moet tussen -90 en 90 liggen.',
            'longitude.numeric' => 'Longitude moet een geldig getal zijn.',
            'longitude.between' => 'Longitude moet tussen -180 en 180 liggen.',
            'birthdate.date' => 'Geboortedatum moet een geldige datum zijn.',
            'active.boolean' => 'De waarde van actief moet een geldige boolean zijn.',
            'bsn.string' => 'BSN moet een geldige tekst zijn.',
            'iban.string' => 'IBAN moet een geldige tekst zijn.',
        ];
    }
}
