<?php

namespace Manta\FluxCMS\Livewire\MantaModule;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Manta\FluxCMS\Models\MantaModule;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;
use Faker\Factory as Faker;

#[Layout('manta-cms::layouts.app')]
class MantaModuleCreate extends Component
{
    use MantaTrait;
    use MantaModuleTrait;

    public function mount()
    {
        if (class_exists(Faker::class) && env('USE_FAKER') == true) {
            $faker = Faker::create('nl_NL');

            $this->name = $faker->slug(2);
            $this->title = $faker->sentence(3);
            $this->description = $faker->paragraph();
            $this->type = $faker->randomElement(['cms', 'webshop', 'tools', 'dev']);
            $this->icon = $faker->randomElement(['home', 'user', 'cog', 'chart']);
            $this->sort = $faker->numberBetween(1, 100);
            $this->module_name = [
                'single' => $faker->word(),
                'plural' => $faker->word() . 's'
            ];
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
            'locale',
            'active',
            'sort',
            'name',
            'title',
            'module_name',
            'tabtitle',
            'description',
            'route',
            'url',
            'icon',
            'type',
            'rights',
            'data',
            'fields',
            'settings',
            'ereg',
        );

        $row['created_by'] = auth('staff')->user()->name;
        $row['host'] = request()->host();

        MantaModule::create($row);

        return $this->redirect(MantaModuleList::class);
    }
}
