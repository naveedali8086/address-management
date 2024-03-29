<?php

namespace Naveedali8086\AddressManagement\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'address-crud:install';

    protected $description = 'Install the CRUD functionality for addresses management';

    public function handle()
    {
        $fileSystem = new Filesystem();

        // copy migrations
        $filePathsTo = File::glob(__DIR__ . '/../../stubs/database/migrations/*.php');
        if ($error = create_table_migration_exists(['countries', 'regions', 'cities', 'addresses'])) {
            $this->info($error);
            return;
        }
        copy_migrations(database_path('migrations'), $filePathsTo);

        // Copy controllers
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Http/Controllers', app_path('Http/Controllers'));

        // Copy requests
        $fileSystem->ensureDirectoryExists(app_path('Http/Requests'));
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Http/Requests', app_path('Http/Requests'));

        // Copy models
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Models', app_path('Models'));

        // Copy enums
        $fileSystem->ensureDirectoryExists(app_path('Enums'));
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Enums', app_path('Enums'));

        // Copy rules
        $fileSystem->ensureDirectoryExists(app_path('Rules'));
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Rules', app_path('Rules'));

        // copy factories
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/database/factories', base_path('database/factories'));

        // copy seeders
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/database/seeders', base_path('database/seeders'));

        // Copy policies
        $fileSystem->ensureDirectoryExists(app_path('Policies'));
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Policies', app_path('Policies'));

        // Copy resources
        $fileSystem->ensureDirectoryExists(app_path('Http/Resources'));
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Http/Resources', app_path('Http/Resources'));

        // copy tests
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/tests/Feature', base_path('tests/Feature'));

        $this->info("address-management scaffolding installed successfully");
    }

}