<?php

namespace Manta\FluxCMS\Livewire\MantaModule;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\MantaModule;
use Illuminate\Http\Request;
use Manta\FluxCMS\Traits\MantaTrait;

#[Layout('manta-cms::layouts.app')]
class MantaModuleRead extends Component
{
    use MantaTrait;
    use MantaModuleTrait;

    public function mount(Request $request, MantaModule $mantaModule)
    {
        $this->setItem($mantaModule);
    }

    public function render()
    {
        return view('manta-cms::livewire.manta-module.manta-module-read')->title($this->item->title . ' bekijken');
    }
}
