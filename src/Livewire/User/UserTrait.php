<?php

namespace Manta\FluxCMS\Livewire\User;

use Manta\FluxCMS\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;

trait UserTrait
{
    public function __construct()
    {
        $this->route_name = 'user';
        $this->route_list = route('manta-cms.' . $this->route_name . '.list');
        $this->config = module_config('User');
        $this->fields = $this->config['fields'];
        $this->moduleClass = 'Manta\Models\User';
    }


    // * Model items
    public ?User $item = null;
    public ?User $itemOrg = null;



    #[Locked]
    public ?string $company_id = null;

    #[Locked]
    public ?string $host = null;

    public ?string $locale = null;
    public ?string $pid = null;

    public ?string $password = null;
    public ?string $name = null;
    public ?string $email = null;
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
    public ?string $country = null;
    public ?string $state = null;
    public ?string $birthdate = null;
    public ?string $birthcity = null;
    public ?string $phone = null;
    public ?string $phone2 = null;
    public ?string $bsn = null;
    public ?string $iban = null;
    public ?string $maritalStatus = null;
    public bool $active = true;
    public bool $admin = false;
    public bool $developer = false;
    public ?string $comments = null;

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
        $return = [];

        $userId = $this->item ? $this->item->id : null;

        if ($this->fields['email']['active'] == true && $this->fields['email']['required'] == true) {
            if ($userId) {
                $return['email'] = 'required|string|email|max:255|unique:users,email,' . $userId;
            } else {
                $return['email'] = 'required|string|email|max:255|unique:users,email';
            }
        }

        if ($this->fields['password']['active'] == true && $this->fields['email']['required'] == true) {
            $return['password'] = ['nullable', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'];
        }

        if ($this->fields['relation_nr']['active'] == true && $this->fields['relation_nr']['required'] == true) {
            $return['relation_nr'] = 'required|string|max:255';
        }
        if ($this->fields['debtor_nr']['active'] == true && $this->fields['debtor_nr']['required'] == true) {
            $return['debtor_nr'] = 'required|string|max:255';
        }
        if ($this->fields['user_nr']['active'] == true && $this->fields['user_nr']['required'] == true) {
            $return['user_nr'] = 'required|string|max:255';
        }
        if ($this->fields['number']['active'] == true && $this->fields['number']['required'] == true) {
            $return['number'] = 'required|string|max:255';
        }
        if ($this->fields['sex']['active'] == true && $this->fields['sex']['required'] == true) {
            $return['sex'] = 'required|string|max:255';
        }
        if ($this->fields['initials']['active'] == true && $this->fields['initials']['required'] == true) {
            $return['initials'] = 'required|string|max:255';
        }
        if ($this->fields['lastname']['active'] == true && $this->fields['lastname']['required'] == true) {
            $return['lastname'] = 'required|string|max:255';
        }
        if ($this->fields['firstnames']['active'] == true && $this->fields['firstnames']['required'] == true) {
            $return['firstnames'] = 'required|string|max:255';
        }
        if ($this->fields['nameInsertion']['active'] == true && $this->fields['nameInsertion']['required'] == true) {
            $return['nameInsertion'] = 'required|string|max:255';
        }
        if ($this->fields['company']['active'] == true && $this->fields['company']['required'] == true) {
            $return['company'] = 'required|string|max:255';
        }
        if ($this->fields['companyNr']['active'] == true && $this->fields['companyNr']['required'] == true) {
            $return['companyNr'] = 'required|string|max:255';
        }
        if ($this->fields['taxNr']['active'] == true && $this->fields['taxNr']['required'] == true) {
            $return['taxNr'] = 'required|string|max:255';
        }
        if ($this->fields['address']['active'] == true && $this->fields['address']['required'] == true) {
            $return['address'] = 'required|string|max:255';
        }
        if ($this->fields['housenumber']['active'] == true && $this->fields['housenumber']['required'] == true) {
            $return['housenumber'] = 'required|string|max:255';
        }
        if ($this->fields['addressSuffix']['active'] == true && $this->fields['addressSuffix']['required'] == true) {
            $return['addressSuffix'] = 'required|string|max:255';
        }
        if ($this->fields['zipcode']['active'] == true && $this->fields['zipcode']['required'] == true) {
            $return['zipcode'] = 'required|string|max:255';
        }
        if ($this->fields['city']['active'] == true && $this->fields['city']['required'] == true) {
            $return['city'] = 'required|string|max:255';
        }
        if ($this->fields['country']['active'] == true && $this->fields['country']['required'] == true) {
            $return['country'] = 'required|string|max:255';
        }
        if ($this->fields['state']['active'] == true && $this->fields['state']['required'] == true) {
            $return['state'] = 'required|string|max:255';
        }
        if ($this->fields['birthdate']['active'] == true && $this->fields['birthdate']['required'] == true) {
            $return['birthdate'] = 'required|string|max:255';
        }
        if ($this->fields['birthcity']['active'] == true && $this->fields['birthcity']['required'] == true) {
            $return['birthcity'] = 'required|string|max:255';
        }
        if ($this->fields['phone']['active'] == true && $this->fields['phone']['required'] == true) {
            $return['phone'] = 'required|string|max:255';
        }
        if ($this->fields['phone2']['active'] == true && $this->fields['phone2']['required'] == true) {
            $return['phone2'] = 'required|string|max:255';
        }
        if ($this->fields['bsn']['active'] == true && $this->fields['bsn']['required'] == true) {
            $return['bsn'] = 'required|string|max:255';
        }
        if ($this->fields['iban']['active'] == true && $this->fields['iban']['required'] == true) {
            $return['iban'] = 'required|string|max:255';
        }
        if ($this->fields['maritalStatus']['active'] == true && $this->fields['maritalStatus']['required'] == true) {
            $return['maritalStatus'] = 'required|string|max:255';
        }
        if ($this->fields['lastLogin']['active'] == true && $this->fields['lastLogin']['required'] == true) {
            $return['lastLogin'] = 'required|string|max:255';
        }
        if ($this->fields['code']['active'] == true && $this->fields['code']['required'] == true) {
            $return['code'] = 'required|string|max:255';
        }
        if ($this->fields['admin']['active'] == true && $this->fields['admin']['required'] == true) {
            $return['admin'] = 'required|string|max:255';
        }
        if ($this->fields['developer']['active'] == true && $this->fields['developer']['required'] == true) {
            $return['developer'] = 'required|string|max:255';
        }
        if ($this->fields['comment']['active'] == true && $this->fields['comment']['required'] == true) {
            $return['comment'] = 'required|string|max:255';
        }
        if ($this->fields['contactperson_id']['active'] == true && $this->fields['contactperson_id']['required'] == true) {
            $return['contactperson_id'] = 'required|string|max:255';
        }
        if ($this->fields['administration']['active'] == true && $this->fields['administration']['required'] == true) {
            $return['administration'] = 'required|string|max:255';
        }
        if ($this->fields['identifier']['active'] == true && $this->fields['identifier']['required'] == true) {
            $return['identifier'] = 'required|string|max:255';
        }



        return $return;
    }

    public function messages()
    {
        $return = [];

        // Foutmeldingen voor 'active'
        $return['active.required'] = 'Het veld "Active" is verplicht.';
        $return['active.boolean'] = 'Het veld "Active" moet waar of onwaar zijn.';

        // Foutmeldingen voor 'relation_nr'
        $return['relation_nr.required'] = 'Het veld "Relatienummer" is verplicht.';
        $return['relation_nr.string'] = 'Het veld "Relatienummer" moet een geldige tekst zijn.';
        $return['relation_nr.max'] = 'Het veld "Relatienummer" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'debtor_nr'
        $return['debtor_nr.required'] = 'Het veld "Debiteurnummer" is verplicht.';
        $return['debtor_nr.string'] = 'Het veld "Debiteurnummer" moet een geldige tekst zijn.';
        $return['debtor_nr.max'] = 'Het veld "Debiteurnummer" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'user_nr'
        $return['user_nr.required'] = 'Het veld "Gebruikersnummer" is verplicht.';
        $return['user_nr.string'] = 'Het veld "Gebruikersnummer" moet een geldige tekst zijn.';
        $return['user_nr.max'] = 'Het veld "Gebruikersnummer" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'number'
        $return['number.required'] = 'Het veld "Nummer" is verplicht.';
        $return['number.string'] = 'Het veld "Nummer" moet een geldige tekst zijn.';
        $return['number.max'] = 'Het veld "Nummer" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'sex'
        $return['sex.required'] = 'Het veld "Geslacht" is verplicht.';
        $return['sex.in'] = 'Het veld "Geslacht" moet male of female zijn.';

        // Foutmeldingen voor 'initials'
        $return['initials.required'] = 'Het veld "Initialen" is verplicht.';
        $return['initials.string'] = 'Het veld "Initialen" moet een geldige tekst zijn.';
        $return['initials.max'] = 'Het veld "Initialen" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'lastname'
        $return['lastname.required'] = 'Het veld "Achternaam" is verplicht.';
        $return['lastname.string'] = 'Het veld "Achternaam" moet een geldige tekst zijn.';
        $return['lastname.max'] = 'Het veld "Achternaam" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'firstnames'
        $return['firstnames.required'] = 'Het veld "Voornamen" is verplicht.';
        $return['firstnames.string'] = 'Het veld "Voornamen" moet een geldige tekst zijn.';
        $return['firstnames.max'] = 'Het veld "Voornamen" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'nameInsertion'
        $return['nameInsertion.required'] = 'Het veld "Tussenvoegsel" is verplicht.';
        $return['nameInsertion.string'] = 'Het veld "Tussenvoegsel" moet een geldige tekst zijn.';
        $return['nameInsertion.max'] = 'Het veld "Tussenvoegsel" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'company'
        $return['company.required'] = 'Het veld "Bedrijfsnaam" is verplicht.';
        $return['company.string'] = 'Het veld "Bedrijfsnaam" moet een geldige tekst zijn.';
        $return['company.max'] = 'Het veld "Bedrijfsnaam" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'companyNr'
        $return['companyNr.required'] = 'Het veld "Bedrijfsnummer" is verplicht.';
        $return['companyNr.string'] = 'Het veld "Bedrijfsnummer" moet een geldige tekst zijn.';
        $return['companyNr.max'] = 'Het veld "Bedrijfsnummer" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'taxNr'
        $return['taxNr.required'] = 'Het veld "BTW-nummer" is verplicht.';
        $return['taxNr.string'] = 'Het veld "BTW-nummer" moet een geldige tekst zijn.';
        $return['taxNr.max'] = 'Het veld "BTW-nummer" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'address'
        $return['address.required'] = 'Het veld "Adres" is verplicht.';
        $return['address.string'] = 'Het veld "Adres" moet een geldige tekst zijn.';
        $return['address.max'] = 'Het veld "Adres" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'housenumber'
        $return['housenumber.required'] = 'Het veld "Huisnummer" is verplicht.';
        $return['housenumber.string'] = 'Het veld "Huisnummer" moet een geldige tekst zijn.';
        $return['housenumber.max'] = 'Het veld "Huisnummer" mag niet langer zijn dan 255 tekens.';

        // Foutmeldingen voor 'zipcode'
        $return['zipcode.required'] = 'Het veld "Postcode" is verplicht.';
        $return['zipcode.string'] = 'Het veld "Postcode" moet een geldige tekst zijn.';
        $return['zipcode.max'] = 'Het veld "Postcode" mag niet langer zijn dan 10 tekens.';

        // Foutmeldingen voor 'city'
        $return['city.required'] = 'Het veld "Stad" is verplicht.';
        $return['city.string'] = 'Het veld "Stad" moet een geldige tekst zijn.';
        $return['city.max'] = 'Het veld "Stad" mag niet langer zijn dan 255 tekens.';

        // Voeg andere foutmeldingen hier toe op dezelfde manier voor de resterende velden.

        return $return;
    }
}
