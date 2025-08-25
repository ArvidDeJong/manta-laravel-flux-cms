<?php

namespace Manta\FluxCMS\Livewire\Company;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\Company;
use Illuminate\Http\Request;
use Manta\FluxCMS\Traits\MantaTrait;

#[Layout('manta-cms::layouts.app')]
class CompanyRead extends Component
{
    use MantaTrait;
    use CompanyTrait;

    public function mount(Request $request, Company $company)
    {
        $this->item = $company;
        $this->id = $company->id;
        $this->data = is_array($company->data) ? $company->data : [];

        // Vul de properties voor readonly weergave
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

        // Meertaligheidsondersteuning
        if ($request->input('locale') && $request->input('locale') != getLocaleManta()) {
            $this->pid = $company->id;
            $this->locale = $request->input('locale');
            $company_translate = Company::where(['pid' => $company->id, 'locale' => $request->input('locale')])->first();
            if ($company_translate) {
                $this->item = $company_translate;
            }
        }

        $this->getLocaleInfo();
        $this->getTablist();
        $this->getBreadcrumb('read');
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-read')->title($this->config['module_name']['single'] . ' bekijken');
    }
}
