<?php

namespace Manta\FluxCMS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use function base_path;
use function public_path;

class CopyLibrariesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta:copy-libraries {--force : Overwrite files in the public directory} {--from-package : Copy from package libs directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy JavaScript libraries from /libs to /public/js/libs';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Determine source path based on --from-package option
        if ($this->option('from-package')) {
            $libsPath = dirname(__DIR__, 3) . '/libs'; // Package libs directory
            $this->info('Copying libraries from package: ' . $libsPath);
        } else {
            $libsPath = base_path('libs'); // Project libs directory
            if (!$this->files->isDirectory($libsPath)) {
                $this->files->makeDirectory($libsPath, 0755, true);
                $this->info('Libs directory created: ' . $libsPath);
            }
        }

        // Check if the public/js/libs directory exists, if not, create it
        $destinationPath = public_path('js/libs');
        if (!$this->files->isDirectory($destinationPath)) {
            $this->files->makeDirectory($destinationPath, 0755, true);
            $this->info('Destination directory created: ' . $destinationPath);
        }

        // Get all files and directories in the libs directory
        if (!$this->files->isDirectory($libsPath)) {
            $this->error('The libs directory does not exist.');
            return 1;
        }

        $libraries = $this->files->allFiles($libsPath);
        
        if (count($libraries) === 0) {
            $this->warn('No libraries found in the libs directory.');
            return 0;
        }

        $copied = 0;
        $skipped = 0;

        // Copy each file to the public directory
        foreach ($libraries as $file) {
            $relativePath = str_replace($libsPath . '/', '', $file->getPathname());
            $destination = $destinationPath . '/' . $relativePath;
            
            // Create the destination directory if it doesn't exist
            $destinationDir = dirname($destination);
            if (!$this->files->isDirectory($destinationDir)) {
                $this->files->makeDirectory($destinationDir, 0755, true);
            }
            
            // Check if the file already exists and if we should overwrite it
            if ($this->files->exists($destination) && !$this->option('force')) {
                $this->line("File skipped (use --force to overwrite): " . $relativePath);
                $skipped++;
                continue;
            }
            
            // Copy the file
            $this->files->copy($file->getPathname(), $destination);
            $this->line("Copied: " . $relativePath);
            $copied++;
        }

        $this->info("Copy operation completed: {$copied} files copied, {$skipped} skipped.");
        return 0;
    }
}
