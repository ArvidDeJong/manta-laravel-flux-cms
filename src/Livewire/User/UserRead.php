<?php

namespace Manta\FluxCMS\Livewire\User;

use Manta\FluxCMS\Models\User;
use Illuminate\Http\Request;
use Livewire\Component;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class UserRead extends Component
{
    use MantaTrait;
    use UserTrait;

    public function mount(Request $request, User $user)
    {
        $this->item = $user;
        $this->itemOrg = $user;
        $this->locale = $user->locale;
        if ($request->input('locale') && $request->input('locale') != getLocaleManta()) {
            $this->pid = $user->id;
            $this->locale = $request->input('locale');
            $user_translate = User::where(['pid' => $user->id, 'locale' => $request->input('locale')])->first();
            $this->item = $user_translate;
        }

        if ($user) {
            $this->id = $user->id;
        }



        $this->getLocaleInfo();
        $this->getTablist();
        $this->getBreadcrumb('read');
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-read')->title($this->config['module_name']['single'] . ' bekijken');
    }
}
