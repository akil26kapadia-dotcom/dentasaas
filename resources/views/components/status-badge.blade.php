@props(['status'])

@php
    $colors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'confirmed' => 'bg-blue-100 text-blue-800',
        'completed' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
        'paid' => 'bg-green-100 text-green-800',
        'unpaid' => 'bg-red-100 text-red-800',
        'planned' => 'bg-purple-100 text-purple-800',
        'in_progress' => 'bg-amber-100 text-amber-800',
        'active' => 'bg-green-100 text-green-800',
        'inactive' => 'bg-red-100 text-red-800',
        'approved' => 'bg-green-100 text-green-800',
        'denied' => 'bg-red-100 text-red-800',
    ];
    $class = $colors[$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $class"]) }}>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
