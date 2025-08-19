<?php

namespace Manta\FluxCMS\Livewire\Company;

use Livewire\Component;
use Manta\FluxCMS\Models\Company;
use Manta\FluxCMS\Traits\MantaTrait;

use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class CompanyUpload extends Component
{
    use MantaTrait;
    use CompanyTrait;

    public function mount(Company $company)
    {
        $this->item = $company;
        $this->itemOrg = $company;
        $this->id = $company->id;
        $this->locale = $company->locale;

        $this->getLocaleInfo();
        $this->getTablist();
        $this->getBreadcrumb('upload');
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-upload')->layoutData(['title' => $this->config['module_name']['single'] . ' bestanden']);
    }
}
