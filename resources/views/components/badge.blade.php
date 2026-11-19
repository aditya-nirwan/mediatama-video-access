@props(['color' => 'gray'])

@php
    $classes = match ($color) {
        'green' => 'bg-emerald-700 text-white',
        'amber' => 'bg-amber-400 text-amber-950',
        'red' => 'bg-rose-700 text-white',
        'blue' => 'bg-blue-700 text-white',
        default => 'bg-gray-600 text-white',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium '.$classes]) }}>
    {{ $slot }}
</span>