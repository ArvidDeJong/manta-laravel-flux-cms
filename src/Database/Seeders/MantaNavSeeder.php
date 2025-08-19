<?php

namespace Manta\FluxCMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Manta\FluxCMS\Models\MantaNav;

class MantaNavSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Controleer of het navigatie-item al bestaat
        $existingNav = MantaNav::where('route', 'manta-cms.manta-nav.list')
            ->where('locale', 'nl')
            ->first();

        if (!$existingNav) {
            MantaNav::create([
                'created_by' => 'Admin Staff',
                'updated_by' => null,
                'deleted_by' => null,
                'company_id' => null,
                'host' => null,
                'pid' => null,
                'locale' => 'nl',
                'active' => true,
                'sort' => 1,
                'title' => 'Navigatie',
                'route' => 'manta-cms.manta-nav.list',
                'url' => null,
                'type' => 'dev',
                'rights' => null,
                'data' => null,
            ]);

            if (isset($this->command)) {
                $this->command->info('MantaNav seeder: Navigatie item aangemaakt.');
            }
        } else {
            if (isset($this->command)) {
                $this->command->info('MantaNav seeder: Navigatie item bestaat al.');
            }
        }
    }
}
