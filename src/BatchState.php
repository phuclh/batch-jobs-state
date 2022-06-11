<?php

namespace Phuclh\BatchJobsState;

use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Bus;
use Phuclh\BatchJobsState\Enums\BatchStateStatus;

class BatchState extends Model
{
    protected $table = 'job_batch_states';

    protected $guarded = [];

    protected $casts = [
        'status' => BatchStateStatus::class,
    ];

    protected ?Batch $cachedBatch = null;

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    public function findBatch(): ?Batch
    {
        if ($this->cachedBatch) {
            return $this->cachedBatch;
        }

        if (!$this->batch_id) {
            return null;
        }

        $this->cachedBatch = Bus::findBatch($this->batch_id);

        return $this->cachedBatch;
    }

    public function progress(): int
    {
        $batch = $this->findBatch();

        return $batch?->progress() ?? 0;
    }

    public function name(): ?string
    {
        $batch = $this->findBatch();

        return $batch?->name;
    }

    public function isProcessed(): bool
    {
        return $this->status === BatchStateStatus::PROCESSED;
    }

    public function isProcessing(): bool
    {
        return $this->status === BatchStateStatus::PROCESSING;
    }
}
