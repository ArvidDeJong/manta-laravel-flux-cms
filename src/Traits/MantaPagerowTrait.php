<?php

namespace Manta\FluxCMS\Traits;

use Flux\Flux;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait MantaPagerowTrait
{

    public ?string $route_name = null;
    public array $fields = [];
    public ?int $tablekey = null;
    public $deleteId;
    public $moduleClass;

    public function remove()
    {
        $this->modal('member-remove')->show();
    }

    public function restore()
    {
        $this->modal('member-restore')->show();
    }
}
