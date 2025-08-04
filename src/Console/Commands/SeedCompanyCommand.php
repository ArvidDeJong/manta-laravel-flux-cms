<?php

namespace Manta\FluxCMS\Console\Commands;

use Illuminate\Console\Command;
use Manta\FluxCMS\Database\seeders\CompanySeeder;

class SeedCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta:seed-company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed a default company if no companies exist in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running Company Seeder...');

        try {
            $seeder = new CompanySeeder();
            $result = $seeder->run();

            if ($result['action'] === 'created') {
                $this->info('✓ ' . $result['message']);
                $this->info('  Company: ' . $result['company']->company);
                $this->info('  Number: ' . $result['company']->number);
                $this->info('  City: ' . $result['company']->city);
            } else {
                $this->info('✓ ' . $result['message']);
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Error running Company Seeder: ' . $e->getMessage());
            return 1;
        }
    }
}
