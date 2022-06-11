<?php

namespace Phuclh\BatchJobsState\Enums;

enum BatchStateStatus: string
{
    case PROCESSING = 'processing';
    case PROCESSED = 'processed';
    case FAILED = 'failed';
}
