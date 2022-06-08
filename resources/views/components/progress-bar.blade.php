@props([
    'name' => null,
    'percent' => 0,
    'batchState' => null
])

@php
    $name = $name ?? $batchState?->name();
    $percent = $percent ?? $batchState?->progress();
@endphp

<div class="flex justify-between mb-1">
    @if ($name)
        <span class="text-base font-medium text-blue-700 dark:text-white">{{ $name }}</span>
    @endif

    <span class="text-sm font-medium text-blue-700 dark:text-white">{{ (int)$percent }}%</span>
</div>
<div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ (int)$percent }}%"></div>
</div>
