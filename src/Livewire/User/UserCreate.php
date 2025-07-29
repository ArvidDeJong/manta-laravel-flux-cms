<?php

namespace Manta\FluxCMS\Livewire\User;

use Faker\Factory as Faker;
use Manta\FluxCMS\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class UserCreate extends Component
{
    use MantaTrait;
    use UserTrait;

    public function mount(Request $request)
    {
        $this->locale = getLocaleManta();
        if ($request->input('locale') && $request->input('pid')) {
            $user = User::find($request->input('pid'));
            $this->pid = $user->id;
            $this->locale = $request->input('locale');
            $this->itemOrg = $user;
        }

        $this->getLocaleInfo();
        $this->getTablist();
        $this->getBreadcrumb('create');

        // Als faker is ingeschakeld, vul de velden automatisch in
        if (Config::get('manta-cms.faker.enabled', false)) {
            $faker = Faker::create('nl_NL');

            // Vul alleen velden in die nog leeg zijn
            if (empty($this->name)) $this->name = $faker->name;
            if (empty($this->email)) $this->email = $faker->unique()->safeEmail;
            if (empty($this->password)) $this->password = $faker->password(12, 12) . 'A1!'; // Complex wachtwoord dat voldoet aan Laravel validatie

            // Voeg meer faker velden toe indien nodig
            if (empty($this->phone)) $this->phone = $faker->phoneNumber;
            if (empty($this->address)) $this->address = $faker->streetName;
            if (empty($this->housenumber)) $this->housenumber = $faker->buildingNumber;
            if (empty($this->zipcode)) $this->zipcode = $faker->postcode;
            if (empty($this->city)) $this->city = $faker->city;
        }
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-create')->title($this->config['module_name']['single'] . ' toevoegen');
    }

    public function save()
    {

        $this->validate();

        $row = $this->only(
            'name',
            'email',
            'company_id',
            'host',
            'locale',
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
            'maritalStatus',
            'comments',
        );
        $row['password'] = Hash::make($this->password);
        $row['created_by'] = auth('staff')->user()->name;
        User::create($row);
        // $this->toastr('success', 'Gebruiker toegevoegd');

        return $this->redirect(UserList::class);
    }
}
