<?php

namespace Codeloops\StarterKit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BlogCommand extends Command
{
    protected $signature = 'starter:blog';
    protected $description = 'Publish blog-related stubs (models, nova resources, policies, migrations)';

    public function handle(): int
    {
        $this->info("🚀 Publishing Blog stubs...");

        try {
            $this->publishBlogStubs();
            $this->info("✅ Blog stubs published successfully!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Failed to publish blog stubs: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }

    protected function publishBlogStubs(): void
    {
        $blogFiles = [
            'models/Post.php' => $this->appPath('Models/Post.php'),
            'models/Category.php' => $this->appPath('Models/Category.php'),
            'nova/Post.php' => $this->appPath('Nova/Post.php'),
            'nova/Category.php' => $this->appPath('Nova/Category.php'),
            'Policies/PostPolicy.php' => $this->appPath('Policies/PostPolicy.php'),
            'Policies/CategoryPolicy.php' => $this->appPath('Policies/CategoryPolicy.php'),
            'migrations/create_posts_table.php' => $this->databasePath('migrations/create_posts_table.php'),
            'migrations/create_categories_table.php' => $this->databasePath('migrations/create_categories_table.php'),
        ];

        foreach ($blogFiles as $source => $destination) {
            $sourcePath = __DIR__ . '/../stubs/' . $source;

            if (file_exists($sourcePath)) {
                $destinationDir = dirname($destination);
                File::ensureDirectoryExists($destinationDir);
                File::copy($sourcePath, $destination);
                $this->info("✅ Published: {$destination}");
            } else {
                $this->warn("⚠️ Source file not found: {$sourcePath}");
            }
        }
    }

    protected function appPath(string $path = ''): string
    {
        return app_path($path);
    }

    protected function databasePath(string $path = ''): string
    {
        return database_path($path);
    }
}
