<?php

namespace Manta\FluxCMS\Livewire\Staff;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Manta\FluxCMS\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Manta\FluxCMS\Traits\MantaTrait;


#[Layout('manta-cms::layouts.app')]
class StaffUpdate extends Component
{
    use MantaTrait;
    use StaffTrait;

    public ?string $password = null;

    public function mount(Staff $staff)
    {
        $this->item = $staff;
        $this->itemOrg = $staff;
        $this->id = $staff->id;

        $this->data = is_array($staff->data) ? $staff->data : [];
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

        // Expliciet het wachtwoord op null zetten voor veiligheid
        $this->password = null;

        $this->getLocaleInfo();
        $this->getTablist([
            [
                'name' => 'Rechten',
                'title' => 'Rechten',
                'tablistShow' => 'rights',
                'url' => route($this->module_routes['rights'], $this->item),
                'active' => false,
            ],
        ]);
        $this->getBreadcrumb('update');
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-update');
    }

    public function save()
    {
        $this->validate();

        $row = $this->only(
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
            'data',
            'rights',
        );

        // Update wachtwoord alleen als er een is opgegeven
        // Dit voorkomt dat null naar de database wordt verzonden
        if (!empty($this->password)) {
            $row['password'] = Hash::make($this->password);
        }

        $this->item->update($row);

        Flux::toast('Opgeslagen', duration: 1000, variant: 'success');
    }
}
