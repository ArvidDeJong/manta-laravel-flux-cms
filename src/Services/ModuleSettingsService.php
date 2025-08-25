<?php

namespace Manta\FluxCMS\Services;

use Illuminate\Support\Facades\Log;
use Manta\FluxCMS\Models\MantaModule;

class ModuleSettingsService
{
    public static function ensureModuleSettings(string $moduleName, string $packagePath = null): array
    {
        $settingsModel = MantaModule::where('name', $moduleName)->first();
        
        if (!$settingsModel && $packagePath) {
            // Try to import settings from the package
            $settingsFile = base_path("vendor/{$packagePath}/export/settings-{$moduleName}.php");
            
            if (file_exists($settingsFile)) {
                $settings = include $settingsFile;
                
                if (is_array($settings)) {
                    $settingsModel = MantaModule::create([
                        'name' => $moduleName,
                        'title' => $settings['title'] ?? ucfirst($moduleName),
                        'locale' => 'nl',
                        'active' => true,
                        'type' => 'module',
                        'fields' => $settings['fields'] ?? [],
                        'module_name' => $settings['module_name'] ?? [
                            'single' => ucfirst($moduleName),
                            'multiple' => ucfirst($moduleName) . 's'
                        ],
                        'tab_title' => $settings['tab_title'] ?? 'title'
                    ]);
                    
                    Log::info("Successfully imported {$moduleName} module settings from file");
                }
            }
        }
        
        if ($settingsModel) {
            return $settingsModel->toArray();
        }
        
        // Return default fallback settings
        Log::warning("Module settings for '{$moduleName}' not found, using defaults");
        
        return [
            'name' => $moduleName,
            'title' => ucfirst($moduleName),
            'module_name' => [
                'single' => ucfirst($moduleName),
                'multiple' => ucfirst($moduleName) . 's'
            ],
            'fields' => [],
            'tab_title' => 'title'
        ];
    }
}
