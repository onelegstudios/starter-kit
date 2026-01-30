<?php

namespace Tests\Feature;

use Tests\TestCase;

class UsingHerdCommandTest extends TestCase
{
    public function test_command_comments_http_line_when_using_herd(): void
    {
        $configPath = config_path('solo.php');
        $originalContent = file_get_contents($configPath);

        // Ensure the line is uncommented before the test
        $content = str_replace(
            "        // 'HTTP' => 'php artisan serve',",
            "        'HTTP' => 'php artisan serve',",
            $originalContent
        );

        file_put_contents($configPath, $content);

        try {
            $this->artisan('sks:using-herd')
                ->expectsConfirmation('Are you using Laravel Herd?', 'yes')
                ->expectsOutput('Successfully disabled HTTP server in solo.php configuration.')
                ->assertExitCode(0);

            $updatedContent = file_get_contents($configPath);
            $this->assertStringContainsString("        // 'HTTP' => 'php artisan serve',", $updatedContent);
            $this->assertStringNotContainsString("        'HTTP' => 'php artisan serve',", $updatedContent);
        } finally {
            file_put_contents($configPath, $originalContent);
        }
    }

    public function test_command_shows_no_changes_needed_when_using_herd_and_already_commented(): void
    {
        $configPath = config_path('solo.php');
        $originalContent = file_get_contents($configPath);

        // Ensure the line is commented
        if (!str_contains($originalContent, "        // 'HTTP' => 'php artisan serve',")) {
            $content = str_replace(
                "        'HTTP' => 'php artisan serve',",
                "        // 'HTTP' => 'php artisan serve',",
                $originalContent
            );
            file_put_contents($configPath, $content);
        }

        try {
            $this->artisan('sks:using-herd')
                ->expectsConfirmation('Are you using Laravel Herd?', 'yes')
                ->expectsOutput('Great! No changes needed.')
                ->assertExitCode(0);

            $updatedContent = file_get_contents($configPath);
            $this->assertStringContainsString("        // 'HTTP' => 'php artisan serve',", $updatedContent);
        } finally {
            file_put_contents($configPath, $originalContent);
        }
    }

    public function test_command_uncomments_http_line_when_not_using_herd(): void
    {
        $configPath = config_path('solo.php');
        $originalContent = file_get_contents($configPath);

        // Ensure the line is commented
        if (!str_contains($originalContent, "        // 'HTTP' => 'php artisan serve',")) {
            $content = str_replace(
                "        'HTTP' => 'php artisan serve',",
                "        // 'HTTP' => 'php artisan serve',",
                $originalContent
            );
            file_put_contents($configPath, $content);
        }

        try {
            $this->artisan('sks:using-herd')
                ->expectsConfirmation('Are you using Laravel Herd?', 'no')
                ->expectsOutput('Successfully enabled HTTP server in solo.php configuration.')
                ->assertExitCode(0);

            $updatedContent = file_get_contents($configPath);
            $this->assertStringContainsString("        'HTTP' => 'php artisan serve',", $updatedContent);
            $this->assertStringNotContainsString("        // 'HTTP' => 'php artisan serve',", $updatedContent);
        } finally {
            file_put_contents($configPath, $originalContent);
        }
    }

    public function test_command_shows_already_uncommented_when_not_using_herd_and_already_uncommented(): void
    {
        $configPath = config_path('solo.php');
        $originalContent = file_get_contents($configPath);

        // Ensure the line is uncommented
        $content = str_replace(
            "        // 'HTTP' => 'php artisan serve',",
            "        'HTTP' => 'php artisan serve',",
            $originalContent
        );
        file_put_contents($configPath, $content);

        try {
            $this->artisan('sks:using-herd')
                ->expectsConfirmation('Are you using Laravel Herd?', 'no')
                ->expectsOutput('The HTTP server line is already uncommented or not found.')
                ->assertExitCode(0);

            // Verify nothing changed
            $updatedContent = file_get_contents($configPath);
            $this->assertStringContainsString("        'HTTP' => 'php artisan serve',", $updatedContent);
            $this->assertStringNotContainsString("        // 'HTTP' => 'php artisan serve',", $updatedContent);
        } finally {
            file_put_contents($configPath, $originalContent);
        }
    }

    public function test_command_fails_when_config_file_not_found(): void
    {
        $configPath = config_path('solo.php');
        $originalContent = file_get_contents($configPath);

        // Temporarily move the config file
        rename($configPath, $configPath . '.backup');

        try {
            $this->artisan('sks:using-herd')
                ->expectsConfirmation('Are you using Laravel Herd?', 'yes')
                ->expectsOutput('Config file solo.php not found.')
                ->assertExitCode(1);
        } finally {
            // Restore the config file
            rename($configPath . '.backup', $configPath);
        }
    }
}
