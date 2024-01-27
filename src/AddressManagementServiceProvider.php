<?php

namespace Naveedali8086\AddressManagement;

use Illuminate\Support\ServiceProvider;
use Naveedali8086\AddressManagement\Console\InstallCommand;

class AddressManagementServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallCommand::class
        ]);
    }

    public function provides()
    {
        return [InstallCommand::class];
    }
}