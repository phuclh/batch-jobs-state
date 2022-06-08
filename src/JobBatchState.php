<?php

namespace Phuclh\BatchJobsState;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Phuclh\BatchJobsState\Enums\BatchStateStatus;

class JobBatchState extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => BatchStateStatus::class,
    ];

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }
}
