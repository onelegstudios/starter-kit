<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Prompts\Prompt;

class UseHerd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'use-herd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure Laravel Herd usage';

    /**
     * Indicates whether the command should be hidden from the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Hide command if not in local environment
        if (!$this->isLocal()) {
            $this->hidden = true;
        }

        $usingHerd = \Laravel\Prompts\confirm('Are you using Laravel Herd?');

        if (!$usingHerd) {
            $this->uncommentHttpCommand();
            $this->info('HTTP command enabled in config/solo.php');
        } else {
            $this->info('Laravel Herd detected. No changes made.');
        }

        return Command::SUCCESS;
    }

    /**
     * Check if the application is running in local environment.
     */
    private function isLocal(): bool
    {
        return app()->environment('local');
    }

    /**
     * Uncomment the HTTP command in config/solo.php.
     */
    private function uncommentHttpCommand(): void
    {
        $configPath = config_path('solo.php');
        $content = file_get_contents($configPath);

        // Replace the commented line with the uncommented version
        $content = str_replace(
            "        // 'HTTP' => 'php artisan serve',",
            "        'HTTP' => 'php artisan serve',",
            $content
        );

        file_put_contents($configPath, $content);
    }
}
