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

    protected ?BatchState $currentBatchState = null;

    protected ?string $taskId = null;

    public function batchStates(): MorphMany
    {
        return $this->morphMany(BatchState::class, 'model');
    }

    public function latestBatchState(): MorphOne
    {
        return $this->morphOne(BatchState::class, 'model')->latestOfMany();
    }

    public function latestProcessingBatchState(): MorphOne
    {
        return $this->morphOne(BatchState::class, 'model')
            ->where('status', BatchStateStatus::PROCESSING)
            ->latestOfMany();
    }

    public function getBatchName(): string
    {
        return get_class($this) . ' - ID: ' . $this->getKey();
    }

    public function taskId(string $taskId): self
    {
        $this->taskId = $taskId;

        if ($this->currentBatchState) {
            $this->currentBatchState->update(['task_id' => $this->currentBatchState]);
        }

        return $this;
    }

    public function newBatch(?string $name = null, bool $allowFailures = true, ?string $queue = null, ?string $taskId = null, ?\Closure $onJobBatchFinallyCallback = null): self
    {
        $taskId && $this->taskId = $taskId;

        $this->currentBatchState = $this->createBatchState();

        $bus = Bus::batch([])
            ->name($name ?? $this->getBatchName())
            ->allowFailures($allowFailures)
            ->finally(fn () => $this->markBatchStateAsProcessed($this->currentBatchState, $onJobBatchFinallyCallback));

        if (! is_null($queue)) {
            $bus->onQueue($queue);
        }

        $this->cachedBatch = $bus->dispatch();

        $this->currentBatchState->update(['batch_id' => $this->cachedBatch->id]);

        return $this;
    }

    public function addJobs(\Closure $callback): Batch
    {
        $callback($this->cachedBatch, $this->currentBatchState);

        $this->cachedBatch->add([
            new MarkAsFullyDispatchedJob($this->currentBatchState),
        ]);

        return $this->cachedBatch;
    }

    public function createBatchState(?Batch $bus = null): BatchState
    {
        return $this->batchStates()->create([
            'task_id' => $this->taskId,
            'batch_id' => $bus?->id,
            'all_jobs_added_to_batch_at' => null,
            'status' => BatchStateStatus::PROCESSING,
        ]);
    }

    public function findBatchState(?string $taskId = null): ?BatchState
    {
        /** @var ?BatchState $batchState */
        $batchState = $taskId
            ? $this->latestBatchState()
                ->where('task_id', $taskId)
                ->latest()
                ->first()
            : $this->latestBatchState;

        return $batchState;
    }

    public function findProcessingBatchState(?string $taskId = null): ?BatchState
    {
        /** @var ?BatchState $batchState */
        $batchState = $taskId
            ? $this->latestBatchState()
                ->where('task_id', $taskId)
                ->where('status', BatchStateStatus::PROCESSING)
                ->latest()
                ->first()
            : $this->latestProcessingBatchState;

        return $batchState;
    }

    protected function markBatchStateAsProcessed(BatchState $batchState, ?\Closure $onJobBatchFinallyCallback = null): void
    {
        $batchState->update(['status' => BatchStateStatus::PROCESSED]);

        if (is_callable($onJobBatchFinallyCallback)) {
            $onJobBatchFinallyCallback($this->cachedBatch, $batchState);
        }
    }
}
