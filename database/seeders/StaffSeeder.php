<?php

namespace Manta\FluxCMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Manta\FluxCMS\Models\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param string|null $email E-mailadres voor de staff gebruiker
     * @param string|null $password Wachtwoord voor de staff gebruiker
     * @return array Logingegevens van de aangemaakte staff gebruiker
     */
    public function run(?string $email = null, ?string $password = null): array
    {
        // Standaard logingegevens als deze niet zijn opgegeven
        $email = $email ?? 'staff@example.com';
        $password = $password ?? $this->generatePassword();
        
        // Controleer of de gebruiker al bestaat
        $staffUser = Staff::where('email', $email)->first();
        
        if ($staffUser) {
            // Update bestaande gebruiker
            $staffUser->update([
                'password' => Hash::make($password),
                'active' => true,
                'admin' => true,
            ]);
        } else {
            // Maak nieuwe gebruiker
            $staffUser = Staff::create([
                'name' => 'Admin Staff',
                'email' => $email,
                'password' => Hash::make($password),
                'active' => true,
                'admin' => true,
                'developer' => true,
                'locale' => 'nl',
            ]);
        }
        
        // Return de logingegevens om in de console te tonen
        return [
            'email' => $email,
            'password' => $password,
            'user' => $staffUser,
        ];
    }
    
    /**
     * Genereer een veilig wachtwoord
     *
     * @param int $length
     * @return string
     */
    protected function generatePassword(int $length = 12): string
    {
        if (function_exists('generatePassword')) {
            return generatePassword($length);
        }
        
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        return $password;
    }
}
