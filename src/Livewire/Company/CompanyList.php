<?php

namespace Manta\FluxCMS\Livewire\Company;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\Company;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;
use Manta\FluxCMS\Livewire\Company\CompanyTrait;

#[Layout('manta-cms::layouts.app')]
class CompanyList extends Component
{
    use MantaTrait;
    use WithPagination;
    use WithSortingTrait;
    use CompanyTrait;

    public function mount()
    {

        $this->getBreadcrumb('list');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->trashed = count(Company::whereNull('pid')->onlyTrashed()->get());

        $obj = Company::query();
        if ($this->tablistShow == 'trashed') {
            $obj->onlyTrashed();
        }
        $obj->when($this->search, function ($query) {
            $query->search($this->search);
        })
            ->orderBy($this->sortBy, $this->sortDirection);
        $companies = $obj->paginate(10);
        return view('manta-cms::livewire.company.company-list', [
            'items' => $companies
        ])->layoutData([
            'title' => 'Company'
        ]);
    }
}
