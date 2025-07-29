<?php

namespace Manta\FluxCMS\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Manta\FluxCMS\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;
use Faker\Factory as Faker;

#[Layout('manta-cms::layouts.app')]
class StaffCreate extends Component
{
    use MantaTrait;
    use StaffTrait;

    public function mount()
    {
        $this->password = generatePassword();

        if (class_exists(Faker::class) && env('USE_FAKER') == true) {
            $faker = Faker::create('NL_nl');
            $this->password = Str::password(12, true, true, true, true); // $faker->password();
        }
        $this->getBreadcrumb('create');
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-create');
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
            'name',
            'email',
            'phone',
            'password',
            'admin',
            'developer',
            'comments',
        );
        $row['created_by'] = auth('staff')->user()->name;
        $row['host'] = request()->host();
        $row['password'] = Hash::make($this->password);
        $row['code'] = Hash::make(time());
        Staff::create($row);

        return $this->redirect(StaffList::class);
    }
}
