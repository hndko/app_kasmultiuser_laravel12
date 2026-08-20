@props([
    'variant' => 'primary', // success, danger, warning, info, purple, gray
    'size' => 'sm',
])

@php
    $variantClass = match($variant) {
        'success' => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400 border border-green-200/50 dark:border-green-500/20',
        'danger', 'error' => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400 border border-red-200/50 dark:border-red-500/20',
        'warning' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400 border border-yellow-200/50 dark:border-yellow-500/20',
        'info', 'blue' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400 border border-blue-200/50 dark:border-blue-500/20',
        'purple' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400 border border-purple-200/50 dark:border-purple-500/20',
        default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700',
    };

    $sizeClass = match($size) {
        'xs' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        default => 'px-2.5 py-0.5 text-xs font-medium',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-full font-medium {$sizeClass} {$variantClass}"]) }}>
    {{ $slot }}
</span>
