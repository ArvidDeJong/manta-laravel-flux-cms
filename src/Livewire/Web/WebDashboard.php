<?php

namespace Manta\FluxCMS\Livewire\Web;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('tuhogroep-theme::layouts.app')]
class WebDashboard extends Component
{
    public function mount()
    {
        // Authenticatie wordt afgehandeld door middleware
    }

    public function render()
    {
        return view('manta-cms::livewire.web.web-dashboard')->title('Dashboard');
    }
}
