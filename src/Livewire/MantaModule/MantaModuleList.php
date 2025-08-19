<?php

namespace Manta\FluxCMS\Livewire\MantaModule;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\MantaModule;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;
use Manta\FluxCMS\Livewire\MantaModule\MantaModuleTrait;

#[Layout('manta-cms::layouts.app')]
class MantaModuleList extends Component
{
    use MantaTrait;
    use WithPagination;
    use WithSortingTrait;
    use MantaModuleTrait;

    public function mount() {}

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        $this->trashed = count(MantaModule::onlyTrashed()->get());

        $obj = MantaModule::query();
        if ($this->tablistShow == 'trashed') {
            $obj->onlyTrashed();
        }
        $obj->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('title', 'LIKE', "%{$this->search}%")
                    ->orWhere('description', 'LIKE', "%{$this->search}%")
                    ->orWhere('type', 'LIKE', "%{$this->search}%");
            });
        })
            ->orderBy($this->sortBy, $this->sortDirection);
        $modules = $obj->paginate(10);

        return view('manta-cms::livewire.manta-module.manta-module-list', [
            'items' => $modules
        ])->layoutData([
            'title' => 'Modules'
        ]);
    }
}
