<?php

namespace Manta\FluxCMS\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\Staff;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;
use Manta\FluxCMS\Livewire\Staff\StaffTrait;

#[Layout('manta-cms::layouts.app')]
class StaffList extends Component
{
    use MantaTrait;
    use WithPagination;
    use WithSortingTrait;
    use StaffTrait;

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
        $this->trashed = count(Staff::whereNull('pid')->onlyTrashed()->get());

        $obj = Staff::query();
        if ($this->tablistShow == 'trashed') {
            $obj->onlyTrashed();
        }
        $obj->when($this->search, function ($query) {
            $query->search($this->search);
        })
            ->orderBy($this->sortBy, $this->sortDirection);
        $staff = $obj->paginate(10);
        return view('manta-cms::livewire.staff.staff-list', [
            'items' => $staff
        ])->layoutData([
            'title' => 'Staff'
        ]);
    }
}
