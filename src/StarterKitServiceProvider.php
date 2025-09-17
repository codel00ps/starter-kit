<?php

namespace Codeloops\StarterKit;

use Illuminate\Support\ServiceProvider;
use Codeloops\StarterKit\Console\CoreCommand;
use Codeloops\StarterKit\Console\PageCommand;
use Codeloops\StarterKit\Console\PermissionsCommand;
use Codeloops\StarterKit\Console\StarterWizardCommand;
use Codeloops\StarterKit\Console\BlogCommand;

class StarterKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CoreCommand::class,
                PageCommand::class,
                PermissionsCommand::class,
                StarterWizardCommand::class,
                BlogCommand::class,
            ]);
        }
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish stubs to the app folders
            $stubsPath = realpath(__DIR__ . '/../stubs');
            if ($stubsPath && is_dir($stubsPath)) {
                $this->publishes([
                    $stubsPath . '/models' => app_path('Models'),
                    $stubsPath . '/nova'   => app_path('Nova'),
                    $stubsPath . '/seeders' => database_path('seeders'),
                    $stubsPath . '/migrations' => database_path('migrations'),
                    $stubsPath . '/Policies' => app_path('Policies'),
                ], 'starter-package-stubs');
            }
        }
    }
}
