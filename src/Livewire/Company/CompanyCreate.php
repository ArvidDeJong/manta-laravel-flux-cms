<?php

namespace Manta\FluxCMS\Livewire\Company;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Manta\FluxCMS\Models\Company;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;
use Faker\Factory as Faker;

#[Layout('manta-cms::layouts.app')]
class CompanyCreate extends Component
{
    use MantaTrait;
    use CompanyTrait;

    public function mount()
    {

        if (class_exists(Faker::class) && env('USE_FAKER') == true) {
            $faker = Faker::create('nl_NL');

            $this->number = $faker->randomNumber(5);
            $this->sex = $faker->randomElement(['m', 'f', 'x']);
            $this->initials = $faker->firstName();
            $this->lastname = $faker->lastName();
            $this->firstnames = $faker->firstName();
            $this->nameInsertion = $faker->optional()->randomElement(['Jr.', 'Sr.', 'I', 'II', 'III']);
            $this->company = $faker->company();
            $this->companyNr = $faker->numerify('########');
            $this->taxNr = $faker->numerify('NL############B##');
            $this->address = $faker->streetAddress();
            $this->housenumber = $faker->buildingNumber();
            $this->addressSuffix = $faker->optional()->randomElement(['A', 'B', 'C', '1', '2', '3', 'bis', 'ter']);
            $this->zipcode = $faker->postcode();
            $this->city = $faker->city();
            $this->country = 'Nederland';
            $this->state = $faker->optional()->state();
            $this->phone = $faker->phoneNumber();
            $this->phone2 = $faker->optional()->phoneNumber();
            $this->bsn = $faker->numerify('#########');
            $this->iban = $faker->iban('NL');
            $this->latitude = $faker->latitude(50.5, 53.7);
            $this->longitude = $faker->longitude(3.2, 7.3);
        }

        $this->getBreadcrumb('create');
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-create');
    }

    public function save()
    {
        $this->validate();

        $row = $this->only(
            'company_id',
            'host',
            'pid',
            'locale',
            'active',
            'administration',
            'identifier',
            'relation_nr',
            'debtor_nr',
            'user_nr',
            'number',
            'sex',
            'initials',
            'lastname',
            'firstnames',
            'nameInsertion',
            'company',
            'companyNr',
            'taxNr',
            'address',
            'housenumber',
            'addressSuffix',
            'zipcode',
            'city',
            'country',
            'state',
            'birthdate',
            'birthcity',
            'phone',
            'phone2',
            'bsn',
            'iban',
            'latitude',
            'longitude',
            'data',
        );

        $row['created_by'] = auth('staff')->user()->name;
        $row['host'] = request()->host();

        Company::create($row);

        return $this->redirect(CompanyList::class);
    }
}
