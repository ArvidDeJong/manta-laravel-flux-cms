<?php

namespace Manta\FluxCMS\Livewire\Routeseo;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\Routeseo;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;

#[Layout('manta-cms::layouts.app')]
class RouteseoList extends Component
{
    use MantaTrait;
    use WithPagination;
    use WithSortingTrait;
    use RouteseoTrait;

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
        $this->trashed = count(Routeseo::onlyTrashed()->get());

        $obj = Routeseo::query();
        if ($this->tablistShow == 'trashed') {
            $obj->onlyTrashed();
        }
        $obj->when($this->search, function ($query) {
            $query->search($this->search);
        })
            ->orderBy($this->sortBy, $this->sortDirection);

        $routeseo = $obj->paginate(10);

        return view('manta-cms::livewire.routeseo.routeseo-list', [
            'items' => $routeseo
        ])->layoutData([
            'title' => 'Route SEO'
        ]);
    }
}
