@props(['label', 'value', 'color' => 'gray'])

<div class="bg-{{ $color }}-100 border-l-4 border-{{ $color }}-500 text-{{ $color }}-700 p-4">
    <div class="text-sm font-semibold">{{ $label }}</div>
    <div class="text-xl font-bold">{{ $value }}</div>
</div>
