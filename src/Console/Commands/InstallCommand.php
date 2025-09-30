<?php

namespace Manta\FluxCMS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Manta\FluxCMS\Database\seeders\CompanySeeder;
use Manta\FluxCMS\Database\seeders\MantaNavSeeder;
use Manta\FluxCMS\Database\seeders\StaffSeeder;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta:install 
                            {--force : Overwrite existing files}
                            {--with-migrations : Also publish migrations}
                            {--with-views : Also publish views}
                            {--skip-provider : Skip registering the ServiceProvider}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure the Flux CMS package';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Flux CMS installation started...');

        // Step 1: Register ServiceProvider
        if (!$this->option('skip-provider')) {
            $this->registerServiceProvider();
        }

        // Step 2: Publish configuration
        $this->publishConfig();

        // Step 3: Publish views (optional)
        if ($this->option('with-views')) {
            $this->publishViews();
        }

        // Step 4: Publish public assets
        $this->publishPublicAssets();

        // Step 5: Publish migrations (optional)
        if ($this->option('with-migrations')) {
            $this->publishMigrations();
        }

        // Step 6: Configure authentication redirects
        $this->configureAuthenticationRedirects();

        // Step 7: Import core module settings
        $this->importCoreModuleSettings();

        // Step 8: Synchronize routes
        $this->syncRoutes();

        // Step 7: Clear cache
        $this->clearCache();

        // Step 8: Run composer dump-autoload
        $this->dumpAutoload();

        // Step 9: Seed database with default data
        $this->seedDatabase();

        $this->info('Flux CMS has been successfully installed!');
        $this->info('You can now view the dashboard at: ' . url(config('manta-cms.prefix', 'cms') . '/dashboard'));
    }

    /**
     * Import core module settings
     */
    protected function importCoreModuleSettings(): void
    {
        $this->info('Importing core module settings...');

        $params = [
            'package' => 'darvis/manta-laravel-flux-cms',
            '--all' => true,
        ];

        if ($this->option('force')) {
            $params['--force'] = true;
        }

        try {
            $this->call('manta:import-module-settings', $params);

            $this->info('Core module settings imported successfully.');
        } catch (\Exception $e) {
            $this->warn('Core module settings import failed: ' . $e->getMessage());
            $this->warn('You can run this manually: php artisan manta:import-module-settings darvis/manta-laravel-flux-cms --all');
        }
    }

    /**
     * Register the ServiceProvider in config/app.php
     */
    protected function registerServiceProvider()
    {
        $appConfig = file_get_contents(config_path('app.php'));

        // Check if the ServiceProvider is already registered
        if (strpos($appConfig, 'Manta\\FluxCMS\\FluxCMSServiceProvider::class') !== false) {
            $this->info('ServiceProvider is already registered.');
            return;
        }

        $this->info('Registering ServiceProvider...');

        // Find the providers array
        $pattern = '/\'providers\'\s*=>\s*\[\s*/';

        if (preg_match($pattern, $appConfig)) {
            // Add the ServiceProvider after the opening of the providers array
            $appConfig = preg_replace(
                $pattern,
                "'providers' => [\n        Manta\\FluxCMS\\FluxCMSServiceProvider::class,\n        ",
                $appConfig,
                1
            );

            // Write the changes back to the file
            file_put_contents(config_path('app.php'), $appConfig);
            $this->info('ServiceProvider successfully registered.');
        } else {
            $this->error('Could not find the providers array in config/app.php. Please add the ServiceProvider manually.');
        }
    }

    /**
     * Publiceer de configuratie
     */
    protected function publishConfig()
    {
        $this->info('Publishing configuration files...');

        if (File::exists(config_path('manta-cms.php')) && !$this->option('force')) {
            if (!$this->confirm('The manta-cms configuration file already exists. Do you want to overwrite it?')) {
                $this->info('Publishing configuration files skipped.');
                return;
            }
        }

        $this->callSilent('vendor:publish', [
            '--provider' => 'Manta\\FluxCMS\\FluxCMSServiceProvider',
            '--tag' => 'config',
            '--force' => $this->option('force'),
        ]);

        $this->info('Configuration files successfully published.');
    }

    /**
     * Publish the view files
     */
    protected function publishViews()
    {
        $this->info('Publishing view files...');

        if (File::exists(resource_path('views/vendor/manta-cms')) && !$this->option('force')) {
            if (!$this->confirm('The manta-cms view files already exist. Do you want to overwrite them?')) {
                $this->info('Publishing view files skipped.');
                return;
            }
        }

        $this->callSilent('vendor:publish', [
            '--provider' => 'Manta\\FluxCMS\\FluxCMSServiceProvider',
            '--tag' => 'views',
            '--force' => $this->option('force'),
        ]);

        $this->info('View files successfully published.');
    }

    /**
     * Publish the public assets
     */
    protected function publishPublicAssets()
    {
        $this->info('Publishing public assets...');

        if (File::exists(public_path('vendor/manta-cms')) && !$this->option('force')) {
            if (!$this->confirm('The manta-cms public assets already exist. Do you want to overwrite them?')) {
                $this->info('Publishing public assets skipped.');
                return;
            }
        }

        $this->callSilent('vendor:publish', [
            '--provider' => 'Manta\\FluxCMS\\FluxCMSServiceProvider',
            '--tag' => 'public',
            '--force' => $this->option('force'),
        ]);

        $this->info('Public assets successfully published.');
    }

    /**
     * Publish the migration files
     */
    protected function publishMigrations()
    {
        $this->info('Publishing migration files...');

        if (count(glob(database_path('migrations/*_manta_cms_*.php'))) > 0 && !$this->option('force')) {
            if (!$this->confirm('Manta-cms migrations already found. Do you want to overwrite them?')) {
                $this->info('Publishing migration files skipped.');
                return;
            }
        }

        $this->callSilent('vendor:publish', [
            '--provider' => 'Manta\\FluxCMS\\FluxCMSServiceProvider',
            '--tag' => 'migrations',
            '--force' => $this->option('force'),
        ]);

        if ($this->confirm('Do you want to run the migrations now?', true)) {
            $this->call('migrate');
        }

        $this->info('Migration files successfully published.');
    }

    /**
     * Synchronize Laravel routes to the database
     */
    protected function syncRoutes()
    {
        $this->info('Synchronizing routes...');

        try {
            $this->call('manta:sync-routes', [
                '--prefix' => 'cms'
            ]);

            $this->info('Routes successfully synchronized.');
        } catch (\Exception $e) {
            $this->warn('Route synchronization skipped: ' . $e->getMessage());
            $this->warn('You can manually run "php artisan manta:sync-routes" later.');
        }
    }

    /**
     * Clear the cache
     */
    protected function clearCache()
    {
        $this->info('Clearing cache...');

        $this->callSilent('view:clear');
        $this->callSilent('cache:clear');
        $this->callSilent('config:clear');

        $this->info('Cache successfully cleared.');
    }

    /**
     * Run composer dump-autoload
     */
    protected function dumpAutoload()
    {
        $this->info('Running composer dump-autoload...');

        // Check if composer is available
        if (shell_exec('which composer')) {
            shell_exec('composer dump-autoload');
            $this->info('Composer dump-autoload successfully executed.');
        } else {
            $this->warn('Composer not found. Please manually run "composer dump-autoload" to load the helpers.');
        }
    }

    /**
     * Seed database with default data
     */
    protected function seedDatabase()
    {
        $this->info('Seeding database with default data...');

        // Seed default company
        $this->seedDefaultCompany();

        // Seed navigation items
        $this->seedNavigation();

        // Seed staff user (optionally)
        if ($this->confirm('Do you want to create a default staff user?', true)) {
            $this->seedStaffUser();
        }

        $this->info('Database seeding completed.');
    }

    /**
     * Seed default company if no companies exist
     */
    protected function seedDefaultCompany()
    {
        $this->info('Checking for default company...');

        try {
            $seeder = new CompanySeeder();
            $result = $seeder->run();

            if ($result['action'] === 'created' && isset($result['company'])) {
                $this->info('✓ ' . $result['message']);
                $this->info('  Company: ' . $result['company']->company);
                $this->info('  Number: ' . $result['company']->number);
            } else {
                $this->info('✓ ' . $result['message']);
            }
        } catch (\Exception $e) {
            $this->warn('Company seeding skipped: ' . $e->getMessage());
            $this->warn('You can manually run the CompanySeeder later if needed.');
        }
    }

    /**
     * Seed navigation items
     */
    protected function seedNavigation()
    {
        $this->info('Seeding navigation items...');

        try {
            $seeder = new MantaNavSeeder();
            $seeder->run();
            $this->info('✓ Navigation items seeded successfully.');
            
            // Seed company navigation item
            $this->call('manta:seed-company');
        } catch (\Exception $e) {
            $this->warn('Navigation seeding skipped: ' . $e->getMessage());
            $this->warn('You can manually run the MantaNavSeeder later if needed.');
        }
    }

    /**
     * Seed default staff user
     */
    protected function seedStaffUser()
    {
        $this->info('Creating default staff user...');

        try {
            $email = $this->ask('Staff email address', 'admin@example.com');
            $password = $this->secret('Staff password (leave empty for auto-generated)');
            
            $seeder = new StaffSeeder();
            $result = $seeder->run($email, $password ?: null);

            $this->info('✓ Staff user created successfully:');
            $this->info('  Email: ' . $result['email']);
            $this->info('  Password: ' . $result['password']);
            $this->warn('Please save these credentials in a secure location!');
        } catch (\Exception $e) {
            $this->warn('Staff user seeding skipped: ' . $e->getMessage());
            $this->warn('You can manually run the StaffSeeder later if needed.');
        }
    }

    /**
     * Configure authentication redirects in bootstrap/app.php
     */
    protected function configureAuthenticationRedirects()
    {
        $this->info('Configuring authentication redirects...');

        $bootstrapPath = base_path('bootstrap/app.php');
        
        if (!File::exists($bootstrapPath)) {
            $this->warn('bootstrap/app.php not found. Skipping authentication redirect configuration.');
            return;
        }

        $content = File::get($bootstrapPath);

        // Check if authentication redirects are already configured
        if (strpos($content, 'redirectGuestsTo') !== false) {
            $this->info('Authentication redirects are already configured.');
            return;
        }

        // Find the withMiddleware section and add the redirect configuration
        $middlewarePattern = '/->withMiddleware\(function \(Middleware \$middleware\) \{([^}]*)\}\)/s';
        
        if (preg_match($middlewarePattern, $content, $matches)) {
            $middlewareContent = $matches[1];
            
            // Add authentication redirect configuration
            $newMiddlewareContent = $middlewareContent . '
        // Configure authentication redirects for different guards
        $middleware->redirectGuestsTo(function ($request) {
            // Check if the request is for staff routes (CMS routes)
            if ($request->is(\'cms/*\') || $request->is(\'medewerkers/*\') || $request->is(\'bedrijven/*\')) {
                return route(\'flux-cms.staff.login\');
            }
            
            // Default redirect for regular users
            return route(\'flux-cms.account.login\');
        });
';

            $newContent = str_replace($matches[0], "->withMiddleware(function (Middleware \$middleware) {{$newMiddlewareContent}    })", $content);
            
            File::put($bootstrapPath, $newContent);
            $this->info('✓ Authentication redirects configured successfully.');
        } else {
            $this->warn('Could not find withMiddleware section in bootstrap/app.php. Please configure authentication redirects manually.');
            $this->info('Add this to your bootstrap/app.php withMiddleware section:');
            $this->info('$middleware->redirectGuestsTo(function ($request) {');
            $this->info('    if ($request->is(\'cms/*\') || $request->is(\'medewerkers/*\') || $request->is(\'bedrijven/*\')) {');
            $this->info('        return route(\'flux-cms.staff.login\');');
            $this->info('    }');
            $this->info('    return route(\'flux-cms.account.login\');');
            $this->info('});');
        }
    }
}
