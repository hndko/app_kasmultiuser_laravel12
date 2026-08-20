@props([
    'name' => 'avatar',
    'label' => 'Foto Profil / Gambar',
    'currentPhotoUrl' => null,
    'required' => false,
    'help' => 'Format JPG, PNG, WEBP max 2MB',
    'errorName' => null,
])

@php
    $actualErrorKey = $errorName ?? $name;
    $hasError = $errors->has($actualErrorKey);
@endphp

<div
    class="w-full"
    x-data="{
        previewUrl: '{{ $currentPhotoUrl }}',
        fileName: '',
        isDragging: false,
        handleFileSelect(event) {
            const file = event.target.files ? event.target.files[0] : (event.dataTransfer ? event.dataTransfer.files[0] : null);
            if (file) {
                this.fileName = file.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        clearPhoto() {
            this.previewUrl = '{{ $currentPhotoUrl }}';
            this.fileName = '';
            $refs.photoInput.value = '';
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

    <div class="flex flex-col sm:flex-row items-center gap-5 p-4 rounded-xl border border-dashed {{ $hasError ? 'border-red-500 bg-red-50/10' : 'border-gray-300 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50' }} transition-all"
        :class="{ 'border-brand-500 bg-brand-50/10': isDragging }"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="isDragging = false; handleFileSelect($event)"
    >
        <!-- Preview Box -->
        <div class="relative shrink-0 flex items-center justify-center">
            <template x-if="previewUrl">
                <div class="relative group">
                    <img :src="previewUrl" alt="Photo Preview" class="w-24 h-24 sm:w-28 sm:h-28 object-cover rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm" />
                    <button
                        type="button"
                        @click.prevent="clearPhoto()"
                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition cursor-pointer"
                        title="Hapus / Reset Foto"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>

            <template x-if="!previewUrl">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl bg-gray-200 dark:bg-gray-800 flex flex-col items-center justify-center text-gray-400 border border-gray-200 dark:border-gray-700">
                    <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-medium">No Preview</span>
                </div>
            </template>
        </div>

        <!-- Upload Trigger & Info -->
        <div class="flex-1 text-center sm:text-left space-y-2">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                <input
                    x-ref="photoInput"
                    type="file"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    accept="image/png,image/jpeg,image/jpg,image/webp"
                    class="hidden"
                    @change="handleFileSelect($event)"
                    {{ $required ? 'required' : '' }}
                />

                <button
                    type="button"
                    @click="$refs.photoInput.click()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.03] shadow-xs transition cursor-pointer"
                >
                    <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span x-text="previewUrl ? 'Ganti Foto' : 'Pilih Foto'">Pilih Foto</span>
                </button>

                <span x-show="fileName" class="text-xs text-brand-600 dark:text-brand-400 font-medium truncate max-w-xs" x-text="fileName"></span>
            </div>

            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ $help }} atau tarik & lepas gambar ke kotak ini.
            </p>
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
