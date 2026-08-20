@props([
    'type' => 'submit',
    'icon' => null,
    'size' => 'md',
    'disabled' => false,
    'href' => null,
])

@php
    $sizeClasses = match($size) {
        'sm' => 'px-3.5 py-2 text-xs',
        'lg' => 'px-6 py-3.5 text-base',
        default => 'px-4 py-2.5 text-sm',
    };

    $baseClass = "inline-flex items-center justify-center gap-2 font-medium rounded-lg bg-red-600 hover:bg-red-700 active:bg-red-800 text-white shadow-theme-xs transition-colors focus:outline-hidden focus:ring-3 focus:ring-red-500/20 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer {$sizeClasses}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClass]) }}>
        @if ($icon)
            <span class="shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $baseClass]) }}>
        @if ($icon)
            <span class="shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
