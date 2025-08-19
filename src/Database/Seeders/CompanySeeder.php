<?php

namespace Manta\FluxCMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Manta\FluxCMS\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $count Number of additional companies to generate
     * @return array Information about the created or existing company
     */
    public function run(int $count = 1): array
    {
        // Check if there are already companies in the database
        $existingCompanies = Company::count();
        
        $createdCompanies = [];
        
        // Create default company if none exists
        if ($existingCompanies === 0) {
            $defaultCompany = Company::create([
                'active' => true,
                'company' => 'Default Company',
                'number' => 'COMP-001',
                'address' => 'Default Street 1',
                'housenumber' => '1',
                'zipcode' => '1000 AA',
                'city' => 'Amsterdam',
                'country' => 'nl',
                'phone' => '+31 20 123 4567',
                'locale' => 'nl',
                'created_by' => 'System',
            ]);
            $createdCompanies[] = $defaultCompany;
        }
        
        // Generate additional companies if count > 1 or if we need to create additional ones
        $additionalCount = $existingCompanies === 0 ? $count - 1 : $count;
        
        if ($additionalCount > 0) {
            $companies = $this->generateCompanies($additionalCount);
            $createdCompanies = array_merge($createdCompanies, $companies);
        }
        
        $totalCreated = count($createdCompanies);
        $totalCompanies = Company::count();
        
        if ($totalCreated > 0) {
            return [
                'action' => 'created',
                'message' => "Created {$totalCreated} companies. Total companies in database: {$totalCompanies}",
                'companies' => $createdCompanies,
                'count' => $totalCreated,
            ];
        } else {
            $firstCompany = Company::first();
            return [
                'action' => 'existing',
                'message' => "Found {$existingCompanies} existing companies in database",
                'company' => $firstCompany,
                'count' => 0,
            ];
        }
    }

    /**
     * Generate multiple companies with realistic data
     *
     * @param int $count Number of companies to generate
     * @return array Array of created Company models
     */
    private function generateCompanies(int $count): array
    {
        $companies = [];
        $companyNames = [
            'Tech Solutions BV',
            'Green Energy Corp',
            'Digital Marketing Pro',
            'Construction Masters',
            'Food & Beverage Co',
            'Transport Services',
            'Healthcare Plus',
            'Education Center',
            'Financial Advisors',
            'Creative Studio',
            'Manufacturing Ltd',
            'Retail Excellence',
            'Consulting Group',
            'Software Development',
            'Real Estate Partners'
        ];

        $cities = [
            ['city' => 'Amsterdam', 'zipcode' => '1000 AA'],
            ['city' => 'Rotterdam', 'zipcode' => '3000 AA'],
            ['city' => 'Den Haag', 'zipcode' => '2500 AA'],
            ['city' => 'Utrecht', 'zipcode' => '3500 AA'],
            ['city' => 'Eindhoven', 'zipcode' => '5600 AA'],
            ['city' => 'Groningen', 'zipcode' => '9700 AA'],
            ['city' => 'Tilburg', 'zipcode' => '5000 AA'],
            ['city' => 'Almere', 'zipcode' => '1300 AA'],
            ['city' => 'Breda', 'zipcode' => '4800 AA'],
            ['city' => 'Nijmegen', 'zipcode' => '6500 AA']
        ];

        for ($i = 0; $i < $count; $i++) {
            $companyNumber = str_pad(Company::count() + $i + 1, 3, '0', STR_PAD_LEFT);
            $cityData = $cities[array_rand($cities)];
            $companyName = $companyNames[array_rand($companyNames)];
            
            // Ensure unique company name
            $originalName = $companyName;
            $counter = 1;
            while (Company::where('company', $companyName)->exists()) {
                $companyName = $originalName . ' ' . $counter;
                $counter++;
            }

            $company = Company::create([
                'active' => true,
                'company' => $companyName,
                'number' => 'COMP-' . $companyNumber,
                'address' => 'Sample Street ' . ($i + 1),
                'housenumber' => (string)($i + 1),
                'zipcode' => $cityData['zipcode'],
                'city' => $cityData['city'],
                'country' => 'nl',
                'phone' => '+31 ' . rand(10, 99) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'locale' => 'nl',
                'created_by' => 'System',
            ]);

            $companies[] = $company;
        }

        return $companies;
    }
}
