<?php

namespace Phuclh\BatchJobsState;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BatchJobsStateServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('batch-jobs-state')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_batch_jobs_state_table');
    }

    public function bootingPackage()
    {
        $this->registerViews();
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'batch-state');
    }
}
