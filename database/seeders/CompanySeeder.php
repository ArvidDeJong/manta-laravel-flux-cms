<?php

namespace Manta\FluxCMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Manta\FluxCMS\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return array Information about the created or existing company
     */
    public function run(): array
    {
        // Check if there are already companies in the database
        $existingCompanies = Company::count();

        if ($existingCompanies > 0) {
            $firstCompany = Company::first();
            return [
                'action' => 'existing',
                'message' => "Found {$existingCompanies} existing companies in database",
                'company' => $firstCompany,
            ];
        }

        // Create default company
        $defaultCompany = Company::create([
            'active' => true,
            'company' => 'Default Company',
            'number' => 'COMP-001',
            'address' => 'Default Street 1',
            'housenumber' => '1',
            'zipcode' => '1000 AA',
            'city' => 'Amsterdam',
            'country' => 'nl',
            'email' => 'info@arvid.nl',
            'phone' => '+31 20 123 4567',
            'locale' => 'nl',
            'created_by' => 'System',
        ]);

        return [
            'action' => 'created',
            'message' => 'Default company created successfully',
            'company' => $defaultCompany,
        ];
    }
}
