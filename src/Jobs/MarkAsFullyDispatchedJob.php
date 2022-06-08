<?php

namespace Phuclh\BatchJobsState\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Phuclh\BatchJobsState\BatchState;

class MarkAsFullyDispatchedJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function __construct(
        private BatchState $batchState
    ) {
    }

    public function handle()
    {
        $this->batchState->update(['all_jobs_added_to_batch_at' => now()]);
    }
}
