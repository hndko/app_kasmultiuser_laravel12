@extends('layouts.app-modules')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Pengaturan Profil
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola informasi akun, foto profil, dan kata sandi Anda.
            </p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $user->role->badgeClass() }}">
                Role: {{ $user->role->label() }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Card 1: Informasi Profil -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-gray-100 dark:border-gray-800 pb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Informasi Profil
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Perbarui nama, email, dan foto avatar akun Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('modules.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
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
                    type="text"
                    label="Nama Lengkap"
                    placeholder="Masukkan nama lengkap Anda"
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

                <div class="flex justify-end pt-2">
                    <!-- Button with Icon Group + Text -->
                    <x-buttons.primary type="submit">
                        <x-slot:icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </x-slot:icon>
                        Simpan Perubahan Profil
                    </x-buttons.primary>
                </div>
            </form>
        </div>

        <!-- Card 2: Ubah Password -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="border-b border-gray-100 dark:border-gray-800 pb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Ganti Kata Sandi
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Pastikan akun menggunakan password yang kuat dan aman.
                </p>
            </div>

            <form method="POST" action="{{ route('modules.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Current Password with Icon Group & Placeholder -->
                <x-forms.input
                    name="current_password"
                    type="password"
                    label="Kata Sandi Saat Ini"
                    placeholder="••••••••"
                    required
                >
                    <x-slot:iconSlot>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>

                <!-- New Password with Icon Group & Placeholder -->
                <x-forms.input
                    name="password"
                    type="password"
                    label="Kata Sandi Baru"
                    placeholder="Minimal 8 karakter"
                    required
                >
                    <x-slot:iconSlot>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>

                <!-- Password Confirmation with Icon Group & Placeholder -->
                <x-forms.input
                    name="password_confirmation"
                    type="password"
                    label="Konfirmasi Kata Sandi Baru"
                    placeholder="Ketik ulang kata sandi baru"
                    required
                >
                    <x-slot:iconSlot>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>

                <div class="flex justify-end pt-2">
                    <!-- Button with Icon Group + Text -->
                    <x-buttons.primary type="submit">
                        <x-slot:icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </x-slot:icon>
                        Perbarui Kata Sandi
                    </x-buttons.primary>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
