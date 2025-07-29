<?php

namespace Manta\FluxCMS\Livewire\Company;

use Manta\FluxCMS\Models\Company;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Locked;
use Manta\FluxCMS\Models\MantaModule;

trait CompanyTrait
{

    public function __construct()
    {
        $this->route_name = 'company';
        $this->route_list = route('manta-cms.' . $this->route_name . '.list');

        $settings = MantaModule::where('name', 'company')->first()->toArray();

        $this->config = $settings;

        $this->fields = $settings['fields'];
        $this->tab_title = isset($settings['tab_title']) ? $settings['tab_title'] : null;

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

    public function rules()
    {
        $itemId = $this->item ? $this->item->id : null;
        $numberUniqueRule = $itemId ? 'unique:companies,number,' . $itemId : 'unique:companies,number';

        $rules = [];

        foreach ($this->fields as $field => $properties) {
            if (!$properties['required']) {
                continue;
            }

            switch ($field) {
                case 'company':
                case 'lastname':
                case 'firstnames':
                    $rules[$field] = 'required|string|max:255';
                    break;
                case 'number':
                    $rules[$field] = 'nullable|string|max:255|' . $numberUniqueRule;
                    break;
                case 'phone':
                case 'phone2':
                    $rules[$field] = 'nullable|string|max:20';
                    break;
                case 'zipcode':
                    $rules[$field] = 'nullable|string|max:10';
                    break;
                case 'city':
                case 'country':
                case 'state':
                    $rules[$field] = 'nullable|string|max:100';
                    break;
                case 'address':
                    $rules[$field] = 'nullable|string|max:500';
                    break;
                case 'latitude':
                    $rules[$field] = 'nullable|numeric|between:-90,90';
                    break;
                case 'longitude':
                    $rules[$field] = 'nullable|numeric|between:-180,180';
                    break;
                case 'birthdate':
                    $rules[$field] = 'nullable|date';
                    break;
                case 'active':
                    $rules[$field] = 'boolean';
                    break;
                case 'bsn':
                    $rules[$field] = 'nullable|string|max:20';
                    break;
                case 'iban':
                    $rules[$field] = 'nullable|string|max:34';
                    break;
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
