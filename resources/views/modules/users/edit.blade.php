@extends('layouts.app-modules')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('modules.users.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-brand-600 dark:text-gray-400 mb-1 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Pengguna
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Edit Pengguna: {{ $user->name }}
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-xs">
        <form method="POST" action="{{ route('modules.users.update', $user) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Photo Upload with LIVE PREVIEW -->
            <x-forms.photo-upload
                name="avatar"
                label="Foto Profil (Avatar)"
                :currentPhotoUrl="$user->avatar_url"
                help="Pilih gambar JPG, PNG, atau WEBP max 2MB"
            />

            <!-- Name Input with Icon Group & Placeholder -->
            <x-forms.input
                name="name"
                label="Nama Lengkap"
                placeholder="cth. Ahmad Fauzi"
                value="{{ old('name', $user->name) }}"
                required
            >
                <x-slot:iconSlot>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </x-slot:iconSlot>
            </x-forms.input>

            <!-- Email Input with Icon Group & Placeholder -->
            <x-forms.input
                name="email"
                type="email"
                label="Alamat Email"
                placeholder="nama@domain.com"
                value="{{ old('email', $user->email) }}"
                required
            >
                <x-slot:iconSlot>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </x-slot:iconSlot>
            </x-forms.input>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Role Select with Icon Group & Placeholder -->
                <x-forms.select
                    name="role"
                    label="Role Pengguna"
                    placeholder="-- Pilih Role --"
                    :selected="old('role', $user->role->value)"
                    required
                >
                    <x-slot:iconSlot>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </x-slot:iconSlot>
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}" {{ old('role', $user->role->value) === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </x-forms.select>

                <!-- Status Select with Icon Group & Placeholder -->
                <x-forms.select
                    name="status"
                    label="Status Akun"
                    placeholder="-- Pilih Status --"
                    :selected="old('status', $user->status->value)"
                    required
                >
                    <x-slot:iconSlot>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </x-slot:iconSlot>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" {{ old('status', $user->status->value) === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </x-forms.select>
            </div>

            <!-- Optional Password Update -->
            <div class="border-t border-gray-100 dark:border-gray-800 pt-4 space-y-4">
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">Ganti Kata Sandi (Opsional)</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ingin mengubah kata sandi pengguna ini.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- New Password with Icon Group & Placeholder -->
                    <x-forms.input
                        type="password"
                        name="password"
                        label="Kata Sandi Baru"
                        placeholder="Minimal 8 karakter"
                    >
                        <x-slot:iconSlot>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </x-slot:iconSlot>
                    </x-forms.input>

                    <!-- Password Confirmation with Icon Group & Placeholder -->
                    <x-forms.input
                        type="password"
                        name="password_confirmation"
                        label="Konfirmasi Kata Sandi Baru"
                        placeholder="Ulangi kata sandi"
                    >
                        <x-slot:iconSlot>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </x-slot:iconSlot>
                    </x-forms.input>
                </div>
            </div>

            <!-- Form Actions with Icon Group + Text -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <x-buttons.secondary :href="route('modules.users.index')">
                    <x-slot:icon>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-slot:icon>
                    Batal
                </x-buttons.secondary>

                <x-buttons.primary type="submit">
                    <x-slot:icon>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </x-slot:icon>
                    Simpan Perubahan
                </x-buttons.primary>
            </div>
        </form>
    </div>
</div>
@endsection
