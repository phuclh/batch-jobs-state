<?php

namespace Phuclh\BatchJobsState;

use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Bus;
use Phuclh\BatchJobsState\Enums\BatchStateStatus;
use Phuclh\BatchJobsState\Jobs\MarkAsFullyDispatchedJob;

trait HasBatchStates
{
    protected ?Batch $cachedBatch = null;

    protected ?JobBatchState $currentBatchState = null;

    protected ?string $taskId = null;

    public function batchStates(): MorphMany
    {
        return $this->morphMany(JobBatchState::class, 'model');
    }

    public function latestBatchState(): MorphOne
    {
        return $this->morphOne(JobBatchState::class, 'model')->latestOfMany();
    }

    public function getBatchName(): string
    {
        return get_class($this) . ' - ID: ' . $this->getKey();
    }

    public function taskId(?string $taskId): self
    {
        $this->taskId = $taskId;

        if ($this->currentBatchState) {
            $this->currentBatchState->update(['task_id' => $this->currentBatchState]);
        }

        return $this;
    }

    public function newBatch(?string $name = null, bool $allowFailures = true, ?string $queue = null): self
    {
        $this->currentBatchState = $this->createBatchState();

        $bus = Bus::batch([])
            ->name($name ?? $this->getBatchName())
            ->allowFailures($allowFailures)
            ->finally(fn() => $this->markBatchStateAsProcessed($this->currentBatchState));

        if (!is_null($queue)) {
            $bus->onQueue($queue);
        }

        $this->cachedBatch = $bus->dispatch();

        $this->currentBatchState->update(['batch_id' => $this->cachedBatch->id]);

        return $this;
    }

    public function addJobs(\Closure $callback): Batch
    {
        $callback($this->cachedBatch);

        $this->cachedBatch->add([
            new MarkAsFullyDispatchedJob($this->currentBatchState)
        ]);

        return $this->cachedBatch;
    }

    public function createBatchState(?Batch $bus = null): JobBatchState
    {
        return $this->batchStates()->create([
            'task_id' => $this->taskId,
            'batch_id' => $bus?->id,
            'all_jobs_added_to_batch_at' => null,
            'status' => BatchStateStatus::PROCESSING,
        ]);
    }

    public function findBatch(): ?Batch
    {
        // Cache the batch.
        if ($this->cachedBatch) {
            return $this->cachedBatch;
        }

        /** @var JobBatchState $batchState */
        $batchState = $this->batchState;

        if (!$batchState) {
            return null;
        }

        $this->cachedBatch = Bus::findBatch($batchState->batch_id);

        return $this->cachedBatch;
    }

    public function onJobBatchFinally(): void
    {
        //
    }

    protected function markBatchStateAsProcessed(JobBatchState $batchState): void
    {
        $batchState->update(['status' => BatchStateStatus::PROCESSED]);

        $this->onJobBatchFinally();
    }
}
