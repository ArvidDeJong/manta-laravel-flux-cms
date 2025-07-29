<?php

namespace Manta\FluxCMS\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\Staff;
use Illuminate\Http\Request;
use Manta\FluxCMS\Traits\MantaTrait;

#[Layout('manta-cms::layouts.app')]
class StaffRead extends Component
{
    use MantaTrait;
    use StaffTrait;

    public function mount(Request $request, Staff $staff)
    {
        $this->item = $staff;
        $this->id = $staff->id;
        $this->data = is_array($staff->data) ? $staff->data : [];
        
        // Vul de properties voor readonly weergave
        $this->fill(
            $staff->only(
                'company_id',
                'host',
                'pid',
                'locale',
                'active',
                'sort',
                'name',
                'email',
                'phone',
                'email_verified_at',
                'lastLogin',
                'code',
                'admin',
                'developer',
                'comments',
            )
        );
        
        // Meertaligheidsondersteuning, vergelijkbaar met UserRead
        if ($request->input('locale') && $request->input('locale') != getLocaleManta()) {
            $this->pid = $staff->id;
            $this->locale = $request->input('locale');
            $staff_translate = Staff::where(['pid' => $staff->id, 'locale' => $request->input('locale')])->first();
            if ($staff_translate) {
                $this->item = $staff_translate;
            }
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
