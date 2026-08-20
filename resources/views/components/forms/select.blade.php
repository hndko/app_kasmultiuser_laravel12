@props([
    'name',
    'label' => null,
    'placeholder' => '-- Pilih Opsi --',
    'required' => false,
    'icon' => null,
    'options' => [],
    'selected' => null,
    'errorName' => null,
    'help' => null,
    'disabled' => false,
])

@php
    $actualErrorKey = $errorName ?? $name;
    $hasError = $errors->has($actualErrorKey);
    $currentValue = old($name, $selected);
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
            <span class="absolute top-1/2 left-3.5 -translate-y-1/2 text-gray-400 pointer-events-none flex items-center justify-center">
                @if (isset($iconSlot))
                    {{ $iconSlot }}
                @else
                    {!! $icon !!}
                @endif
            </span>
        @endif

        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'h-11 w-full appearance-none rounded-lg border bg-transparent pr-10 text-sm text-gray-800 transition-colors focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 ' .
                $plClass . ' ' .
                ($hasError
                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500/10 dark:border-red-500'
                    : 'border-gray-300 focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-500')
            ]) }}
        >
            @if ($placeholder)
                <option value="" class="text-gray-400 dark:bg-gray-900">{{ $placeholder }}</option>
            @endif

            @if (!empty($options))
                @foreach ($options as $key => $val)
                    @php
                        $optVal = is_string($key) ? $key : (is_array($val) ? ($val['value'] ?? $key) : $val);
                        $optLabel = is_array($val) ? ($val['label'] ?? $val['name'] ?? $optVal) : $val;
                        $isSelected = (string)$currentValue === (string)$optVal;
                    @endphp
                    <option value="{{ $optVal }}" {{ $isSelected ? 'selected' : '' }} class="dark:bg-gray-900">
                        {{ $optLabel }}
                    </option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>

        <!-- Chevron Down Arrow -->
        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
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
