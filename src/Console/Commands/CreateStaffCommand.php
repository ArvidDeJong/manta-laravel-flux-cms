<?php

namespace Manta\FluxCMS\Console\Commands;

use Faker\Factory as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Manta\FluxCMS\Database\seeders\StaffSeeder;

class CreateStaffCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta:create-staff
                           {--email= : Email address for the staff user}
                           {--password= : Password for the staff user (optional, otherwise a password will be generated)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a staff user for testing purposes (only available in local environment)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if we are in the local environment
        if (!App::environment('local')) {
            $this->error('This command can only be executed in the local development environment.');
            return 1;
        }

        $this->info('Creating staff user...');
        
        // Get parameters
        $email = $this->option('email');
        $password = $this->option('password');
        
        // Ask for email if not provided
        if (!$email) {
            // Generate a random email address as suggestion
            $faker = Faker::create('NL_nl');
            $suggestedEmail = $faker->safeEmail();
            $email = $this->ask('Enter an email address for the staff user', $suggestedEmail);
        }
        
        // Run StaffSeeder
        $seeder = new StaffSeeder();
        $result = $seeder->run($email, $password);
        
        // Show login credentials
        $this->info('Staff user successfully created!');
        $this->info('======================================');
        $this->info('Login credentials:');
        $this->info('Email: ' . $result['email']);
        $this->info('Password: ' . $result['password']);
        $this->info('======================================');
        $this->info('You can now log in as a staff user.');
        
        return 0;
    }
}
