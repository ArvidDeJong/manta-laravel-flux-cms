<?php

namespace Manta\FluxCMS\Console\Commands;

use Illuminate\Console\Command;
use Manta\FluxCMS\Database\seeders\MantaNavSeeder;

class SeedNavigationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta:seed-navigation 
                            {--force : Force seeding even if navigation items already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with default navigation items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧭 Seeding Manta Navigation...');
        $this->newLine();

        try {
            $this->seedNavigation();
            
            $this->newLine();
            $this->info('🎉 Navigation seeding completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error during navigation seeding: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Seed navigation items
     */
    protected function seedNavigation()
    {
        $this->info('📋 Creating navigation items...');

        try {
            $seeder = new MantaNavSeeder();
            $seeder->run();
            $this->info('   ✅ Navigation items seeded successfully.');
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Navigation seeding failed: ' . $e->getMessage());
            $this->warn('   💡 You can manually run the MantaNavSeeder later if needed.');
            throw $e; // Re-throw to be caught by handle()
        }
    }
}
