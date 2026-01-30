<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\confirm;

class UsingHerdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sks:using-herd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure Solo based on Laravel Herd usage';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $usingHerd = confirm(
            label: 'Are you using Laravel Herd?',
            default: true
        );

        $configPath = config_path('solo.php');

        if (! file_exists($configPath)) {
            $this->error('Config file solo.php not found.');

            return self::FAILURE;
        }

        $content = file_get_contents($configPath);

        if ($usingHerd) {
            $updated = str_replace(
                "        'HTTP' => 'php artisan serve',",
                "        // 'HTTP' => 'php artisan serve',",
                $content
            );

            if ($content === $updated) {
                $this->info('Great! No changes needed.');

                return self::SUCCESS;
            }

            file_put_contents($configPath, $updated);
            $this->info('Successfully disabled HTTP server in solo.php configuration.');

            return self::SUCCESS;
        }

        $updated = str_replace(
            "        // 'HTTP' => 'php artisan serve',",
            "        'HTTP' => 'php artisan serve',",
            $content
        );

        if ($content === $updated) {
            $this->warn('The HTTP server line is already uncommented or not found.');

            return self::SUCCESS;
        }

        file_put_contents($configPath, $updated);
        $this->info('Successfully enabled HTTP server in solo.php configuration.');

        return self::SUCCESS;
    }
}
