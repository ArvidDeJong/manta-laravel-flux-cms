<?php

namespace Manta\FluxCMS\Traits;

use Livewire\Attributes\Url;

trait WithSortingTrait
{
    // #[Url]
    // public $sortCol;

    // #[Url]
    // public $sortAsc = false;

    // public function sortBy($column)
    // {
    //     if ($this->sortCol === $column) {
    //         $this->sortAsc = !$this->sortAsc;
    //     } else {
    //         $this->sortCol = $column;
    //         $this->sortAsc = false;
    //     }
    // }

    protected function applySorting($query)
    {
        if ($this->sortBy) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query;
    }

    #[Url]
    public $sortBy = 'created_at';

    #[Url]
    public $sortDirection = 'desc';

    public function dosort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function sortBy($column)
    {
        $this->dosort($column);
    }
}
