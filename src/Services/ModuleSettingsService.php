<?php

namespace Manta\FluxCMS\Services;

use Illuminate\Support\Facades\Log;
use Manta\FluxCMS\Models\MantaModule;

class ModuleSettingsService
{
    public static function ensureModuleSettings(string $moduleName, string $packagePath = null): array
    {
        $settingsModel = MantaModule::where('name', $moduleName)->first();
        
        // Load settings from file if package path is provided
        $fileSettings = null;
        if ($packagePath) {
            $settingsFile = base_path("vendor/{$packagePath}/export/settings-{$moduleName}.php");
            if (file_exists($settingsFile)) {
                $fileSettings = include $settingsFile;
                if (!is_array($fileSettings)) {
                    $fileSettings = null;
                }
            }
        }

        // Only create if module doesn't exist
        if (!$settingsModel && $fileSettings) {
            // Create new module if it doesn't exist and we have file settings
            $createData = [
                'name' => $moduleName,
                'locale' => 'nl',
                'active' => true,
                'type' => 'module',
            ];
            
            // Get fillables and casts from a temporary model instance
            $tempModel = new MantaModule();
            $fillables = $tempModel->getFillable();
            $casts = $tempModel->getCasts();
            
            // Map file settings to model attributes
            $fieldMapping = [
                'locale' => 'locale',
                'active' => 'active',
                'sort' => 'sort',
                'name' => 'name',
                'title' => 'title',
                'module_name' => 'module_name',
                'tab_title' => 'tabtitle',
                'tabtitle' => 'tabtitle',
                'description' => 'description',
                'route' => 'route',
                'url' => 'url',
                'icon' => 'icon',
                'type' => 'type',
                'rights' => 'rights',
                'data' => 'data',
                'fields' => 'fields',
                'settings' => 'settings',
                'ereg' => 'ereg',
            ];
            
            // Add all available fillable fields from file settings
            foreach ($fieldMapping as $fileKey => $modelAttribute) {
                if (isset($fileSettings[$fileKey]) && in_array($modelAttribute, $fillables)) {
                    $value = $fileSettings[$fileKey];
                    
                    // Apply casts if defined
                    if (isset($casts[$modelAttribute])) {
                        $castType = $casts[$modelAttribute];
                        
                        switch ($castType) {
                            case 'array':
                                $value = is_array($value) ? $value : [];
                                break;
                            case 'boolean':
                                $value = (bool) $value;
                                break;
                            case 'integer':
                                $value = (int) $value;
                                break;
                        }
                    }
                    
                    $createData[$modelAttribute] = $value;
                }
            }
            
            // Set defaults for required fields if not provided
            $createData['title'] = $createData['title'] ?? ucfirst($moduleName);
            $createData['tabtitle'] = $createData['tabtitle'] ?? 'title';
            $createData['module_name'] = $createData['module_name'] ?? [
                'single' => ucfirst($moduleName),
                'multiple' => ucfirst($moduleName) . 's'
            ];
            
            $settingsModel = MantaModule::create($createData);

            Log::info("Successfully created {$moduleName} module settings from file");
        } elseif ($settingsModel && $fileSettings) {
            // Update existing module with file settings, respecting fillables and casts
            $updateData = [];
            $fillables = $settingsModel->getFillable();
            $casts = $settingsModel->getCasts();
            
            // Map file settings to model attributes
            $fieldMapping = [
                'locale' => 'locale',
                'active' => 'active',
                'sort' => 'sort',
                'name' => 'name',
                'title' => 'title',
                'module_name' => 'module_name',
                'tab_title' => 'tabtitle',
                'tabtitle' => 'tabtitle',
                'description' => 'description',
                'route' => 'route',
                'url' => 'url',
                'icon' => 'icon',
                'type' => 'type',
                'rights' => 'rights',
                'data' => 'data',
                'fields' => 'fields',
                'settings' => 'settings',
                'ereg' => 'ereg',
            ];
            
            foreach ($fieldMapping as $fileKey => $modelAttribute) {
                if (isset($fileSettings[$fileKey]) && in_array($modelAttribute, $fillables)) {
                    $value = $fileSettings[$fileKey];
                    $currentValue = $settingsModel->getAttribute($modelAttribute);
                    
                    // Only update if current value is empty OR if field should be array but isn't
                    $shouldUpdate = false;
                    
                    // Check if current value is empty (null, empty string, empty array)
                    if (is_null($currentValue) || $currentValue === '' || (is_array($currentValue) && empty($currentValue))) {
                        $shouldUpdate = true;
                    }
                    
                    // Check if field should be array but current value is not an array
                    if (isset($casts[$modelAttribute]) && $casts[$modelAttribute] === 'array' && !is_array($currentValue)) {
                        $shouldUpdate = true;
                    }
                    
                    if ($shouldUpdate) {
                        // Apply casts if defined
                        if (isset($casts[$modelAttribute])) {
                            $castType = $casts[$modelAttribute];
                            
                            switch ($castType) {
                                case 'array':
                                    $value = is_array($value) ? $value : [];
                                    break;
                                case 'boolean':
                                    $value = (bool) $value;
                                    break;
                                case 'integer':
                                    $value = (int) $value;
                                    break;
                            }
                        }
                        
                        $updateData[$modelAttribute] = $value;
                    }
                }
            }
            
            if (!empty($updateData)) {
                $settingsModel->update($updateData);
                Log::info("Successfully updated {$moduleName} module settings from file");
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
