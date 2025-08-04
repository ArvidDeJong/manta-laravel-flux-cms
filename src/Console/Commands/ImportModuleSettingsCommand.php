<?php

namespace Manta\FluxCMS\Console\Commands;

use Illuminate\Console\Command;
use Manta\FluxCMS\Models\MantaModule;

class ImportModuleSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta:import-module-settings
                            {package : The package name (e.g., darvis/manta-contact)}
                            {--settings-file=export/settings.php : Relative path to settings file within package}
                            {--all : Import all settings files from the export directory}
                            {--force : Overwrite existing module settings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import module settings from any package into MantaModule model';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // LIKE: php artisan manta:import-module-settings darvis/manta-laravel-flux-cms --all
        $packageName = $this->argument('package');
        $importAll = $this->option('all');

        $this->info("Importing module settings from package: {$packageName}");

        // Construct path to package
        $packagePath = base_path("vendor/{$packageName}");

        if (!is_dir($packagePath)) {
            $this->error("Package directory not found: {$packagePath}");
            return 1;
        }

        if ($importAll) {
            return $this->importAllSettingsFiles($packagePath, $packageName);
        } else {
            $settingsFile = $this->option('settings-file');
            return $this->importSingleSettingsFile($packagePath, $settingsFile, $packageName);
        }
    }

    /**
     * Import all settings files from the export directory
     */
    private function importAllSettingsFiles(string $packagePath, string $packageName): int
    {
        $exportPath = $packagePath . '/export';

        if (!is_dir($exportPath)) {
            $this->error("Export directory not found: {$exportPath}");
            return 1;
        }

        // Find all PHP files in the export directory
        $settingsFiles = glob($exportPath . '/settings*.php');

        if (empty($settingsFiles)) {
            $this->warn('No settings files found in export directory.');
            $this->info('Looking for files matching pattern: settings*.php');
            return 1;
        }

        $this->info("Found " . count($settingsFiles) . " settings file(s) to import:");
        foreach ($settingsFiles as $file) {
            $this->line('  - ' . basename($file));
        }
        $this->newLine();

        $successCount = 0;
        $failureCount = 0;

        foreach ($settingsFiles as $settingsPath) {
            $fileName = basename($settingsPath);
            $this->info("Processing: {$fileName}");

            $result = $this->processSettingsFile($settingsPath, $packageName);

            if ($result === 0) {
                $successCount++;
                $this->info("✅ Successfully imported: {$fileName}");
            } else {
                $failureCount++;
                $this->error("❌ Failed to import: {$fileName}");
            }
            $this->newLine();
        }

        // Summary
        $this->info("Import completed!");
        $this->info("✅ Successful: {$successCount}");
        if ($failureCount > 0) {
            $this->error("❌ Failed: {$failureCount}");
        }

        // Sync routes once after all imports
        if ($successCount > 0) {
            $this->info('\nSynchronizing routes...');
            $this->call('manta:sync-routes');
        }

        return $failureCount > 0 ? 1 : 0;
    }

    /**
     * Import a single settings file
     */
    private function importSingleSettingsFile(string $packagePath, string $settingsFile, string $packageName): int
    {
        $settingsPath = $packagePath . '/' . $settingsFile;

        if (!file_exists($settingsPath)) {
            $this->error("Settings file not found at: {$settingsPath}");
            $this->info("Expected path: {$settingsFile}");
            return 1;
        }

        $result = $this->processSettingsFile($settingsPath, $packageName);

        // Sync routes after successful import
        if ($result === 0) {
            $this->info('\nSynchronizing routes...');
            $this->call('manta:sync-routes');
        }

        return $result;
    }

    /**
     * Process a single settings file
     */
    private function processSettingsFile(string $settingsPath, string $packageName): int
    {
        // Load settings from package
        $settings = include $settingsPath;

        if (!is_array($settings)) {
            $this->error('Settings file must return an array');
            return 1;
        }

        // Validate required settings
        if (!isset($settings['name'])) {
            $this->error('Settings must contain "name" key');
            return 1;
        }

        // Use the name directly for checking uniqueness
        $moduleName = $settings['name'];

        // Check if module already exists
        $existingModule = MantaModule::where('name', $moduleName)->first();

        if ($existingModule && !$this->option('force')) {
            $this->warn('Module already exists. Use --force to overwrite.');
            $this->info("Existing module: {$existingModule->title} (ID: {$existingModule->id})");
            return 1;
        }

        // Prepare data for insertion/update
        $moduleData = $this->prepareModuleData($settings, $packageName);

        try {
            if ($existingModule) {
                // Update existing module
                $existingModule->update($moduleData);
                $this->info('Successfully updated existing module settings.');
                $moduleId = $existingModule->id;
            } else {
                // Create new module
                $module = MantaModule::create($moduleData);
                $this->info('Successfully imported module settings.');
                $moduleId = $module->id;
            }

            // Display summary
            $this->displayImportSummary($moduleData, $moduleId);
        } catch (\Exception $e) {
            $this->error('Failed to import settings: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Prepare module data from settings array
     */
    private function prepareModuleData(array $settings, string $packageName): array
    {
        // Use all settings directly, with fallbacks for required fields
        $moduleData = [];

        // Get all fillable fields from MantaModule
        $fillableFields = [
            'created_by',
            'updated_by',
            'deleted_by',
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
            'ereg'
        ];

        // Map all available settings to module data
        foreach ($fillableFields as $field) {
            if (isset($settings[$field])) {
                $moduleData[$field] = $settings[$field];
            }
        }

        // Set defaults for required fields if not provided
        $moduleData['description'] = $moduleData['description'] ?? "Module geïmporteerd vanuit {$packageName}";
        $moduleData['type'] = $moduleData['type'] ?? 'modules';
        $moduleData['active'] = $moduleData['active'] ?? true;
        $moduleData['sort'] = $moduleData['sort'] ?? 999;
        $moduleData['locale'] = $moduleData['locale'] ?? 'nl';

        return $moduleData;
    }

    /**
     * Display import summary
     */
    private function displayImportSummary(array $moduleData, int $moduleId): void
    {
        $this->newLine();
        $this->info("Module imported with ID: {$moduleId}");

        $this->table(
            ['Field', 'Value'],
            [
                ['ID', $moduleId],
                ['Tab Title', $moduleData['tabtitle'] ?? 'N/A'],
                ['Module Name', is_array($moduleData['name']) ? 'JSON Array' : $moduleData['name']],
                ['Title', $moduleData['title']],
                ['Type', $moduleData['type']],
                ['Active', $moduleData['active'] ? 'Yes' : 'No'],
                ['Locale', $moduleData['locale']],
                ['EREG', empty($moduleData['ereg']) ? 'Empty' : 'JSON data imported'],
                ['Settings', empty($moduleData['settings']) ? 'Empty' : 'JSON data imported'],
                ['Fields', empty($moduleData['fields']) ? 'Empty' : 'JSON data imported'],
            ]
        );
    }
}
