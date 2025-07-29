<?php

namespace Manta\FluxCMS\Livewire\Company;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Manta\FluxCMS\Models\Company;
use Manta\FluxCMS\Traits\MantaTrait;

#[Layout('manta-cms::layouts.app')]
class CompanyUpdate extends Component
{
    use MantaTrait;
    use CompanyTrait;

    public function mount(Company $company)
    {
        $this->item = $company;
        $this->itemOrg = $company;
        $this->id = $company->id;

        $this->data = is_array($company->data) ? $company->data : [];
        $this->fill(
            $company->only(
                'company_id',
                'host',
                'pid',
                'locale',
                'active',
                'sort',
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
            )
        );

        $this->getLocaleInfo();
        $this->getBreadcrumb('update');
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-update');
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
            'sort',
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

        $this->item->update($row);

        return $this->redirect(CompanyList::class);
    }
}
