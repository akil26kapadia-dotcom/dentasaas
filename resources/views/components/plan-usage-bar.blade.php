@props(['usage' => []])

@php
    $maxPct = collect($usage)->max('pct') ?? 0;
@endphp

@if ($maxPct > 70)
    <div {{ $attributes->merge(['class' => 'space-y-2']) }}>
        @foreach ($usage as $resource => $data)
            @php
                $color = $data['pct'] >= 90 ? 'bg-red-500' : ($data['pct'] >= 70 ? 'bg-amber-500' : 'bg-green-500');
            @endphp
            <div>
                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                    <span class="capitalize">{{ $resource }}</span>
                    <span>{{ $data['used'] }}/{{ $data['limit'] === -1 ? '∞' : $data['limit'] }}</span>
                </div>
                <div class="w-full h-1.5 rounded-full bg-gray-200 dark:bg-gray-700">
                    <div class="h-1.5 rounded-full {{ $color }}" style="width: {{ $data['pct'] }}%"></div>
                </div>
            </div>
        @endforeach

        <a href="{{ Route::has('settings.billing') ? route('settings.billing') : '#' }}"
           class="block text-center mt-2 text-xs font-medium bg-indigo-600 text-white rounded-lg py-1.5 hover:bg-indigo-700">
            Upgrade Plan
        </a>
    </div>
@endif
