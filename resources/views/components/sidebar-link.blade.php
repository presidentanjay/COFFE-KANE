@props(['href', 'icon', 'label'])

@php
    $isActive = request()->url() === $href;
@endphp

<a href="{{ $href }}"
   class="flex flex-col items-center text-sm py-3 px-2 rounded-lg transition
          {{ $isActive ? 'bg-pink-500 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
    {{-- Tampilkan ikon HTML secara mentah (non-escaped) --}}
    <span class="text-xl">{!! $icon !!}</span>

    {{-- Tampilkan label --}}
    <span class="text-xs mt-1 text-center leading-tight">
        {{ $label }}
    </span>
</a>
