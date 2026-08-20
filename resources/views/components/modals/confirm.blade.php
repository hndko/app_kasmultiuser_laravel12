@props([
    'id' => 'confirm-modal',
    'title' => 'Konfirmasi Tindakan',
    'message' => 'Apakah Anda yakin ingin melanjutkan tindakan ini? Tindakan ini tidak dapat dibatalkan.',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batal',
    'confirmVariant' => 'danger', // danger, primary
])

<div
    x-data="{ open: false, formAction: '', messageText: '{{ $message }}' }"
    @open-confirm-modal.window="
        open = true;
        formAction = $event.detail.action || '';
        if ($event.detail.message) messageText = $event.detail.message;
    "
    x-show="open"
    class="fixed inset-0 z-99999 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-xs"
    style="display: none;"
    x-cloak
>
    <!-- Modal Backdrop / Box -->
    <div
        @click.away="open = false"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-theme-xl border border-gray-200 dark:border-gray-800 space-y-4"
    >
        <!-- Icon & Header -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full {{ $confirmVariant === 'danger' ? 'bg-red-100 dark:bg-red-500/10 text-red-600' : 'bg-brand-100 dark:bg-brand-500/10 text-brand-600' }} flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ $title }}
                </h3>
            </div>
        </div>

        <!-- Body Message -->
        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="messageText">
            {{ $message }}
        </p>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <x-buttons.secondary @click="open = false">
                {{ $cancelText }}
            </x-buttons.secondary>

            <form :action="formAction" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <x-buttons.danger type="submit">
                    {{ $confirmText }}
                </x-buttons.danger>
            </form>
        </div>
    </div>
</div>
