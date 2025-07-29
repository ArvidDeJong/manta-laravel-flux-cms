<?php

namespace Manta\FluxCMS\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Flux\Flux;
use Manta\FluxCMS\Models\Staff;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Livewire\Staff\StaffTrait;

#[Layout('manta-cms::layouts.app')]
class StaffRights extends Component
{
    use MantaTrait;
    use StaffTrait;


    public array $rights = [];

    // Available rights structure
    public array $availableRights = [];

    public function mount(Staff $staff)
    {
        $this->item = $staff;
        $this->itemOrg = $staff;

        // Set available rights from config
        $configRights = config('manta-cms.rights') ?? [];
        $this->availableRights = [];

        // Transform config structure to usable format
        foreach ($configRights as $category => $categoryData) {
            $this->availableRights[$category] = [
                'label' => $categoryData['label'],
                'description' => $categoryData['description'] ?? '',
                'rights' => []
            ];

            // Transform rights array to key-value pairs
            foreach ($categoryData['rights'] as $right) {
                $this->availableRights[$category]['rights'][$right['key']] = $right['label'];
            }
        }

        // Set current staff rights
        $this->rights = $staff->rights ?? [];

        $this->getLocaleInfo();

        $this->getTablist([
            [
                'name' => 'rights',
                'title' => 'Rechten',
                'tablistShow' => 'rights',
                'url' => route('manta-cms.staff.rights', [$this->route_name => $this->item]),
                'active' => true,
            ],
        ]);
        $this->tablistModuleShow = 'rights';

        $this->getBreadcrumb('update');
    }


    public function save()
    {
        $this->item->update([
            'rights' => $this->rights
        ]);

        Flux::toast(variant: 'success', text: __('manta-cms::messages.changes_saved'));
    }

    public function hasRight($category, $right)
    {
        return isset($this->rights[$category][$right]) && $this->rights[$category][$right] === true;
    }



    public function render()
    {
        return view('manta-cms::livewire.staff.staff-rights');
    }
}
