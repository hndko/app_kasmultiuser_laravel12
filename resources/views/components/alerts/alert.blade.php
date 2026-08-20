@props([
    'type' => 'success', // success, error, warning, info
    'title' => null,
    'message' => null,
    'dismissible' => true,
])

@php
    $typeConfig = match($type) {
        'error', 'danger' => [
            'bg' => 'bg-red-50 dark:bg-red-500/10',
            'border' => 'border-red-200 dark:border-red-500/20',
            'text' => 'text-red-800 dark:text-red-300',
            'icon' => '<svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            'defaultTitle' => 'Terjadi Kesalahan!',
        ],
        'warning' => [
            'bg' => 'bg-amber-50 dark:bg-amber-500/10',
            'border' => 'border-amber-200 dark:border-amber-500/20',
            'text' => 'text-amber-800 dark:text-amber-300',
            'icon' => '<svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
            'defaultTitle' => 'Peringatan!',
        ],
        'info' => [
            'bg' => 'bg-blue-50 dark:bg-blue-500/10',
            'border' => 'border-blue-200 dark:border-blue-500/20',
            'text' => 'text-blue-800 dark:text-blue-300',
            'icon' => '<svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            'defaultTitle' => 'Informasi',
        ],
        default => [
            'bg' => 'bg-green-50 dark:bg-green-500/10',
            'border' => 'border-green-200 dark:border-green-500/20',
            'text' => 'text-green-800 dark:text-green-300',
            'icon' => '<svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            'defaultTitle' => 'Berhasil!',
        ],
    };

    $alertTitle = $title ?? ($message ? null : $typeConfig['defaultTitle']);
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    class="flex items-start gap-3 p-4 rounded-xl border {{ $typeConfig['bg'] }} {{ $typeConfig['border'] }} {{ $typeConfig['text'] }} shadow-xs"
>
    {!! $typeConfig['icon'] !!}

    <div class="flex-1 text-sm">
        @if ($alertTitle)
            <h5 class="font-semibold mb-0.5">{{ $alertTitle }}</h5>
        @endif
        @if ($message)
            <p>{{ $message }}</p>
        @else
            {{ $slot }}
        @endif
    </div>

    @if ($dismissible)
        <button
            type="button"
            @click="show = false"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-0.5 transition cursor-pointer"
            title="Tutup"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
