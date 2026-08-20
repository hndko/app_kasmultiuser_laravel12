@props([
    'title' => 'Belum ada data',
    'message' => 'Data belum tersedia untuk ditampilkan.',
    'icon' => null,
    'actionUrl' => null,
    'actionText' => null,
    'actionIcon' => null,
])

<div class="flex flex-col items-center justify-center p-8 sm:p-12 text-center">
    <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 flex items-center justify-center mb-4">
        @if ($icon)
            {!! $icon !!}
        @else
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        @endif
    </div>

    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90 mb-1">
        {{ $title }}
    </h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-5">
        {{ $message }}
    </p>

    @if ($actionUrl && $actionText)
        <x-buttons.primary :href="$actionUrl" :icon="$actionIcon">
            {{ $actionText }}
        </x-buttons.primary>
    @elseif ($slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>
