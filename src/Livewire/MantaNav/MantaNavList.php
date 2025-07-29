<?php

namespace Manta\FluxCMS\Livewire\MantaNav;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\MantaNav;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;
use Illuminate\Support\Facades\Route;
use Manta\FluxCMS\Models\MantaRoute;
use Flux\Flux;

#[Layout('manta-cms::layouts.app')]
class MantaNavList extends Component
{

    use MantaTrait;
    use WithPagination;
    use WithSortingTrait;
    use MantaNavTrait;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $items = MantaNav::query()
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        $routes = MantaRoute::where('active', true)->whereLike('prefix', 'cms%')->orderBy('name', 'asc')->get();

        return view('manta-cms::livewire.manta-nav.manta-nav-list', [
            'items' => $items,
            'routes' => $routes
        ])->title('Navigatie');
    }

    public function store()
    {

        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
        ]);

        MantaNav::create([
            'title' => $this->title,
            'route' => $this->route,
            'url' => $this->url,
            'type' => $this->type,
            'sort' => $this->sort,
            'active' => $this->active,
            'locale' => $this->locale ?? 'nl',
        ]);

        $this->reset(['title', 'route', 'url', 'type']);

        $this->redirect(route('manta-cms.manta-nav.list'));
    }

    public function toggleActive($id)
    {
        $item = MantaNav::findOrFail($id);
        $item->update(['active' => !$item->active]);

        $status = $item->active ? 'geactiveerd' : 'gedeactiveerd';
        session()->flash('message', "Navigatie-item '{$item->title}' is {$status}.");
    }
}
