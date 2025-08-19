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
    protected $signature = 'manta:seed-company {--count=1 : Number of additional companies to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed a default company and optionally generate additional companies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');
        
        if ($count < 1) {
            $this->error('Count must be at least 1');
            return 1;
        }

        $this->info("Running Company Seeder (generating {$count} companies)...");

        try {
            $seeder = new CompanySeeder();
            $result = $seeder->run($count);

            if ($result['action'] === 'created') {
                $this->info('✓ ' . $result['message']);
                
                // Show details of created companies
                if (isset($result['companies'])) {
                    foreach ($result['companies'] as $company) {
                        $this->info("  📢 {$company->company} ({$company->number}) - {$company->city}");
                    }
                } elseif (isset($result['company'])) {
                    $this->info("  📢 {$result['company']->company} ({$result['company']->number}) - {$result['company']->city}");
                }
            } else {
                $this->info('✓ ' . $result['message']);
                if ($count > 1) {
                    $this->info("💡 Use --count={$count} to generate {$count} additional companies anyway");
                }
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Error running Company Seeder: ' . $e->getMessage());
            return 1;
        }
    }
}
