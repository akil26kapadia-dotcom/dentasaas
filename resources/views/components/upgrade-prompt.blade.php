@props(['usage' => []])

@php
    $maxPct = collect($usage)->max('pct') ?? 0;
    $resource = collect($usage)->sortByDesc('pct')->keys()->first();
@endphp

@if ($maxPct >= 80 && $resource)
    <div
        {{ $attributes->merge(['class' => 'flex items-center justify-between gap-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 mb-4 text-sm']) }}>
        <div class="flex items-center gap-2 text-amber-800">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>You've used {{ $usage[$resource]['pct'] }}% of your {{ str_replace('_', ' ', $resource) }} limit for
                this plan.</span>
        </div>
        <a href="{{ Route::has('settings.billing') ? route('settings.billing') : '#' }}"
            class="shrink-0 text-xs font-medium bg-amber-600 text-white rounded-lg px-3 py-1.5 hover:bg-amber-700">
            Upgrade
        </a>
    </div>
@endif
