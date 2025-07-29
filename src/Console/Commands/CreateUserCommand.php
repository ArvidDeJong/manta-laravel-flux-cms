<?php

namespace Manta\FluxCMS\Console\Commands;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta:create-user
                           {--name= : Naam voor de gebruiker}
                           {--email= : E-mailadres voor de gebruiker}
                           {--password= : Wachtwoord voor de gebruiker (optioneel, anders wordt een wachtwoord gegenereerd)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Maak een gebruiker aan voor testdoeleinden (alleen beschikbaar in lokale omgeving)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Controleren of we in de lokale omgeving zijn
        if (!App::environment('local')) {
            $this->error('Dit commando kan alleen in de lokale ontwikkelomgeving worden uitgevoerd.');
            return 1;
        }

        $this->info('Gebruiker aanmaken...');

        // Parameters ophalen
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');

        // Faker instantie voor random data
        $faker = Faker::create('NL_nl');

        // Naam opvragen als deze niet is opgegeven
        if (!$name) {
            $suggestedName = $faker->name();
            $name = $this->ask('Geef een naam voor de gebruiker', $suggestedName);
        }

        // E-mail opvragen als deze niet is opgegeven
        if (!$email) {
            $suggestedEmail = $faker->safeEmail();
            $email = $this->ask('Geef een e-mailadres voor de gebruiker', $suggestedEmail);
        }

        // Wachtwoord genereren als deze niet is opgegeven
        if (!$password) {
            $password = Str::password(10);
        }

        // Gebruiker aanmaken
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Toon de logingegevens
        $this->info('Gebruiker succesvol aangemaakt!');
        $this->info('======================================');
        $this->info('Logingegevens:');
        $this->info('Naam: ' . $user->name);
        $this->info('E-mail: ' . $user->email);
        $this->info('Wachtwoord: ' . $password);
        $this->info('======================================');
        $this->info('Je kunt nu inloggen met deze gebruiker.');

        return 0;
    }
}
