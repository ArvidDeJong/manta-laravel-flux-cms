<?php

namespace Manta\FluxCMS\Livewire\User;

use Manta\FluxCMS\Models\User;
use Livewire\Component;
use Manta\FluxCMS\Traits\SortableTrait;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class UserList extends Component
{
    use UserTrait;
    use WithPagination;
    use SortableTrait;
    use MantaTrait;
    use WithSortingTrait;

    public function mount()
    {
        $this->getBreadcrumb();
    }

    public function render()
    {
        $this->trashed = count(User::whereNull('pid')->onlyTrashed()->get());

        $obj = User::whereNull('pid');
        if ($this->tablistShow == 'trashed') {
            $obj->onlyTrashed();
        }
        $obj = $this->applySorting($obj);
        $obj = $this->applySearch($obj);
        $items = $obj->paginate(50);
        return view('manta-cms::livewire.user.user-list', ['items' => $items])->title('Klanten');
    }
}
