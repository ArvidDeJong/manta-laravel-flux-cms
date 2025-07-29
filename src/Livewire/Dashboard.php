<?php

namespace Manta\FluxCMS\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        return view('manta-cms::livewire.dashboard');
    }
}
