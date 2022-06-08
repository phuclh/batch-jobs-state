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
            ->hasMigration('create_batch_jobs_state_table');
    }
}
