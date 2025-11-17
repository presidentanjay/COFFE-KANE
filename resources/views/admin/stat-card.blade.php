@props(['label', 'value', 'icon' => '📊'])

<div class="p-5 bg-gray-800 rounded-xl shadow border border-gray-700 flex items-center justify-between">
    <div>
        <p class="text-sm text-gray-400">{{ $label }}</p>
        <p class="text-2xl font-bold text-white">{{ $value }}</p>
    </div>
    <div class="text-3xl">
        <span>{{ $icon }}</span>
    </div>
</div>
