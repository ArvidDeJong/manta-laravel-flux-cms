<?php

namespace Manta\FluxCMS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\File;

class RefreshCommand extends Command
{
    /**
     * De naam en signature van het commando.
     *
     * @var string
     */
    protected $signature = 'manta:refresh {--no-build : Voer npm run build niet uit}';

    /**
     * De omschrijving van het commando.
     *
     * @var string
     */
    protected $description = 'Voer composer dump-autoload, Laravel cache clears en optioneel npm run build in één keer uit';

    /**
     * Voer het commando uit.
     */
    public function handle()
    {
        $this->info('🔄 FluxCMS Refresh starten...');

        $this->info('🧹 Autoload opnieuw genereren...');
        $this->executeCommand('composer dump-autoload');

        $this->info('🧹 Laravel cache wissen...');
        $this->executeCommand('php artisan cache:clear');
        $this->executeCommand('php artisan view:clear');
        $this->executeCommand('php artisan config:clear');
        $this->executeCommand('php artisan route:clear');

        if (!$this->option('no-build')) {
            if (File::exists(getcwd() . '/package.json')) {
                $this->info('🏗️ Front-end assets bouwen...');
                $this->executeCommand('npm run build');
            } else {
                $this->warn('⚠️ Geen package.json gevonden, npm run build wordt overgeslagen.');
            }
        }

        $this->newLine();
        $this->info('✅ FluxCMS Refresh voltooid!');

        return Command::SUCCESS;
    }

    /**
     * Voer een shell commando uit en toon de output.
     *
     * @param string $command
     * @return bool
     */
    private function executeCommand($command)
    {
        $process = Process::run($command, function ($type, $output) {
            $this->output->write($output);
        });

        if (!$process->successful()) {
            $this->error("❌ Commando mislukt: {$command}");
            return false;
        }

        return true;
    }
}
