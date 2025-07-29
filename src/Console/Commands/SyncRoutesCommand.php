<?php

namespace Manta\FluxCMS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Manta\FluxCMS\Models\MantaRoute;

class SyncRoutesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta:sync-routes
                           {--prefix= : Filter routes by prefix (e.g. cms)}
                           {--clear : Remove existing routes before synchronization}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Laravel routes to the MantaRoute table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Synchronizing routes...');

        // Optionally remove existing routes
        if ($this->option('clear')) {
            $this->info('Removing existing routes...');
            MantaRoute::truncate();
        }

        // Get all Laravel routes
        $routes = Route::getRoutes();
        $prefixFilter = $this->option('prefix');
        
        $syncedCount = 0;
        $skippedCount = 0;
        $createdCount = 0;
        $updatedCount = 0;
        
        // Collect all current Laravel route identifiers
        $currentRoutes = [];

        foreach ($routes as $route) {
            $routeData = [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'prefix' => $route->getPrefix(),
                'active' => 1,
            ];

            // Filter by prefix if specified
            if ($prefixFilter && $route->getPrefix() !== $prefixFilter) {
                $skippedCount++;
                continue;
            }

            // Store route identifier for cleanup later
            $currentRoutes[] = [
                'uri' => $routeData['uri'],
                'name' => $routeData['name']
            ];

            // Check if route already exists (based on uri and name)
            $existingRoute = MantaRoute::where('uri', $routeData['uri'])
                ->where('name', $routeData['name'])
                ->first();

            if ($existingRoute) {
                // Update existing route
                $existingRoute->update($routeData);
                $this->line("Updated: {$routeData['uri']} ({$routeData['name']})");
                $updatedCount++;
            } else {
                // Create new route
                MantaRoute::create($routeData);
                $this->line("Created: {$routeData['uri']} ({$routeData['name']})");
                $createdCount++;
            }

            $syncedCount++;
        }

        // Remove routes that no longer exist in Laravel
        $this->info('Checking for obsolete routes...');
        $deletedCount = $this->removeObsoleteRoutes($currentRoutes, $prefixFilter);

        $this->info('======================================');
        $this->info("Route synchronization completed!");
        $this->info("Created: {$createdCount} routes");
        $this->info("Updated: {$updatedCount} routes");
        $this->info("Deleted: {$deletedCount} routes");
        $this->info("Total synchronized: {$syncedCount} routes");
        
        if ($prefixFilter) {
            $this->info("Filtered by prefix: {$prefixFilter}");
            $this->info("Skipped: {$skippedCount} routes");
        }
        
        $this->info('======================================');

        return 0;
    }

    /**
     * Remove routes from database that no longer exist in Laravel
     *
     * @param array $currentRoutes
     * @param string|null $prefixFilter
     * @return int
     */
    private function removeObsoleteRoutes(array $currentRoutes, ?string $prefixFilter = null): int
    {
        $query = MantaRoute::query();
        
        // Apply prefix filter if specified
        if ($prefixFilter) {
            $query->where('prefix', $prefixFilter);
        }
        
        $existingRoutes = $query->get();
        $deletedCount = 0;
        
        foreach ($existingRoutes as $existingRoute) {
            $routeExists = false;
            
            // Check if this route still exists in current Laravel routes
            foreach ($currentRoutes as $currentRoute) {
                if ($existingRoute->uri === $currentRoute['uri'] && 
                    $existingRoute->name === $currentRoute['name']) {
                    $routeExists = true;
                    break;
                }
            }
            
            // If route doesn't exist anymore, delete it
            if (!$routeExists) {
                $this->line("Deleted: {$existingRoute->uri} ({$existingRoute->name})");
                $existingRoute->delete();
                $deletedCount++;
            }
        }
        
        return $deletedCount;
    }
}
