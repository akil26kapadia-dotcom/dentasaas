@props(['status'])

@php
    $colors = [
        'pending' => 'bg-warning-50 text-warning-600',
        'confirmed' => 'bg-blue-light-100 text-blue-light-600',
        'completed' => 'bg-success-50 text-success-600',
        'cancelled' => 'bg-error-50 text-error-600',
        'paid' => 'bg-success-50 text-success-600',
        'unpaid' => 'bg-error-50 text-error-600',
        'planned' => 'bg-indigo-50 text-indigo-600',
        'in_progress' => 'bg-warning-50 text-warning-600',
        'active' => 'bg-success-50 text-success-600',
        'inactive' => 'bg-error-50 text-error-600',
        'approved' => 'bg-success-50 text-success-600',
        'denied' => 'bg-error-50 text-error-600',
    ];
    $class = $colors[$status] ?? 'bg-gray-100 text-gray-600';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $class"]) }}>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
