@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'value' => null,
    'rows' => 3,
    'required' => false,
    'icon' => null,
    'errorName' => null,
    'help' => null,
    'disabled' => false,
    'readonly' => false,
])

@php
    $actualErrorKey = $errorName ?? $name;
    $hasError = $errors->has($actualErrorKey);
    $inputValue = old($name, $value ?? '');
    $plClass = ($icon || isset($iconSlot)) ? 'pl-11' : 'pl-4';
@endphp

<div class="w-full">
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if ($icon || isset($iconSlot))
            <span class="absolute top-3.5 left-3.5 text-gray-400 pointer-events-none flex items-center justify-center">
                @if (isset($iconSlot))
                    {{ $iconSlot }}
                @else
                    {!! $icon !!}
                @endif
            </span>
        @endif

        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder ?: ($label ? 'Masukkan ' . strtolower($label) : '') }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full rounded-lg border bg-transparent py-2.5 pr-4 text-sm text-gray-800 transition-colors placeholder:text-gray-400 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 ' .
                $plClass . ' ' .
                ($hasError
                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500/10 dark:border-red-500'
                    : 'border-gray-300 focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-500')
            ]) }}
        >{{ $inputValue }}</textarea>
    </div>

    @if ($help && !$hasError)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif

    @error($actualErrorKey)
        <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 inline-block shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
