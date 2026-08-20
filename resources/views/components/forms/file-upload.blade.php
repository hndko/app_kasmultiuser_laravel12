@props([
    'name' => 'attachment',
    'label' => 'Unggah Berkas',
    'accept' => '*/*',
    'required' => false,
    'help' => 'Seret & lepas berkas ke area ini atau klik untuk memilih berkas',
    'errorName' => null,
])

@php
    $actualErrorKey = $errorName ?? $name;
    $hasError = $errors->has($actualErrorKey);
@endphp

<div
    class="w-full"
    x-data="{
        fileName: '',
        fileSize: '',
        isDragging: false,
        handleDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.setFile(file);
                $refs.fileInput.files = event.dataTransfer.files;
            }
        },
        handleSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.setFile(file);
            }
        },
        setFile(file) {
            this.fileName = file.name;
            const sizeInKb = (file.size / 1024).toFixed(1);
            this.fileSize = sizeInKb > 1024 ? (sizeInKb / 1024).toFixed(2) + ' MB' : sizeInKb + ' KB';
        },
        clearFile() {
            this.fileName = '';
            this.fileSize = '';
            $refs.fileInput.value = '';
        }
    }"
>
    @if ($label)
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="handleDrop($event)"
        @click="$refs.fileInput.click()"
        :class="isDragging
            ? 'border-brand-500 bg-brand-50/10'
            : '{{ $hasError ? 'border-red-500 bg-red-50/10' : 'border-gray-300 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50' }}'"
        class="border border-dashed rounded-xl p-6 text-center cursor-pointer transition-colors hover:border-brand-500 group"
    >
        <input
            x-ref="fileInput"
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="{{ $accept }}"
            class="hidden"
            @change="handleSelect($event)"
            {{ $required ? 'required' : '' }}
        />

        <div class="flex flex-col items-center justify-center">
            <!-- Icon -->
            <div class="w-12 h-12 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
            </div>

            <!-- Upload Status / File Info -->
            <template x-if="fileName">
                <div class="space-y-1">
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-sm font-semibold text-gray-800 dark:text-white/90" x-text="fileName"></span>
                        <button
                            type="button"
                            @click.stop="clearFile()"
                            class="text-red-500 hover:text-red-700 p-0.5"
                            title="Hapus berkas"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400" x-text="fileSize"></span>
                </div>
            </template>

            <template x-if="!fileName">
                <div>
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white/90 mb-1">
                        <span class="text-brand-500 font-semibold underline">Pilih berkas</span> atau seret ke sini
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
                </div>
            </template>
        </div>
    </div>

    @error($actualErrorKey)
        <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 inline-block shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
