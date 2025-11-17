@props(['href', 'active' => false])

@php
    $classes = ($active ?? false)
        ? 'text-pink-400 font-semibold'
        : 'text-white hover:text-pink-400';

    $baseClasses = 'flex items-center gap-2 px-4 py-2 rounded transition duration-200 ' . $classes;
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
    {{ $slot }}
</a>
