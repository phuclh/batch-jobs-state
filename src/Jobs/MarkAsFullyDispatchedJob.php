<?php

namespace Phuclh\BatchJobsState\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Phuclh\BatchJobsState\JobBatchState;

class MarkAsFullyDispatchedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public function __construct(
        private JobBatchState $jobBatchState
    ) {
    }

    public function handle()
    {
        $this->jobBatchState->update(['all_jobs_added_to_batch_at' => now()]);
    }
}
