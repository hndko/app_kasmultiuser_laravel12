@extends('layouts.app-auth')

@section('content')
<div class="max-w-md mx-auto p-6 sm:p-8">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-theme-lg space-y-6">
        
        <!-- Header / Brand -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-500 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                Daftar Akun Baru
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Buat akun pengguna Sistem Kas Anda
            </p>
        </div>

        @if ($errors->any())
            <x-alerts.alert type="error" :message="$errors->first()" />
        @endif

        <!-- Register Form -->
        <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
            @csrf

            <!-- Name Input with Icon Group & Placeholder -->
            <x-forms.input
                name="name"
                type="text"
                label="Nama Lengkap"
                placeholder="cth. Ahmad Fauzi"
                value="{{ old('name') }}"
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
                value="{{ old('email') }}"
                required
            >
                <x-slot:iconSlot>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </x-slot:iconSlot>
            </x-forms.input>

            <!-- Password Input with Icon Group & Placeholder -->
            <x-forms.input
                name="password"
                type="password"
                label="Kata Sandi"
                placeholder="Minimal 8 karakter"
                required
            >
                <x-slot:iconSlot>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </x-slot:iconSlot>
            </x-forms.input>

            <!-- Password Confirmation with Icon Group & Placeholder -->
            <x-forms.input
                name="password_confirmation"
                type="password"
                label="Ulangi Kata Sandi"
                placeholder="Ketik ulang kata sandi"
                required
            >
                <x-slot:iconSlot>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </x-slot:iconSlot>
            </x-forms.input>

            <!-- Submit Button with Icon Group + Text -->
            <x-buttons.primary type="submit" class="w-full">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </x-slot:icon>
                Daftar Akun
            </x-buttons.primary>
        </form>

        <!-- Login Link -->
        <div class="pt-2 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400 hover:underline">
                Masuk di Sini
            </a>
        </div>
    </div>
</div>
@endsection
