<?php

namespace Manta\FluxCMS\Livewire\Upload;

use Livewire\Component;
use Manta\FluxCMS\Models\Upload;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Traits\SortableTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;

#[Layout('manta-cms::layouts.app')]
class UploadList extends Component
{
    use WithPagination;
    use SortableTrait;
    use MantaTrait;
    use WithSortingTrait;
    use UploadTrait;

    public function mount()
    {
        $this->getBreadcrumb();
        $this->sortBy = 'created_at';
        $this->sortDirection = 'DESC';
    }

    public function render()
    {
        $this->trashed = count(Upload::whereNull('pid')->onlyTrashed()->get());

        $obj = Upload::query();
        $obj = $this->applySorting($obj);
        $obj = $this->applySearch($obj);
        if ($this->tablistShow == 'trashed') {
            $obj->onlyTrashed();
        }
        $items = $obj->paginate(20);

        return view('manta-cms::livewire.upload.upload-list', compact('items'))->layoutData(['title' => 'Uploads']);
    }
}
