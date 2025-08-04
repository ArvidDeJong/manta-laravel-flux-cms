<?php

namespace Manta\FluxCMS\Livewire\Cms;

use Livewire\Component;

class CmsDashboard extends Component
{
    public function render()
    {
        return view('manta-cms::livewire.cms.cms-dashboard')->title('Dashboard');
    }
}
