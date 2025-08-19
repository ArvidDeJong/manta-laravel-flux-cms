<?php

namespace Manta\FluxCMS\Console\Commands;

use Illuminate\Console\Command;

class SeedCompanyNavigationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta:seed-company-navigation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed company navigation item if it does not exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏢 Seeding company navigation item...');

        try {
            // Check if MantaNav model exists
            if (!class_exists('\Manta\FluxCMS\Models\MantaNav')) {
                $this->warn('   ⚠️  MantaNav model not found. Cannot seed company navigation.');
                return 1;
            }

            $MantaNav = '\Manta\FluxCMS\Models\MantaNav';

            $companyNavItem = [
                'title' => 'Bedrijven',
                'route' => 'manta-cms.company.list',
                'sort' => 2,
                'type' => 'dev',
                'description' => 'Beheer bedrijven'
            ];

            // Check if navigation item already exists
            $existingItem = $MantaNav::where('route', $companyNavItem['route'])->first();

            if ($existingItem) {
                $this->info('   ✓ Company navigation item already exists');
                $this->info("     Title: {$existingItem->title}");
                $this->info("     Route: {$existingItem->route}");
                return 0;
            }

            // Create the navigation item
            $navItem = $MantaNav::create($companyNavItem);

            $this->info('   ✅ Company navigation item created successfully');
            $this->info("     Title: {$navItem->title}");
            $this->info("     Route: {$navItem->route}");
            $this->info("     Sort: {$navItem->sort}");
            $this->info("     Type: {$navItem->type}");

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error seeding company navigation: ' . $e->getMessage());
            return 1;
        }
    }
}
