@extends('layouts.app-auth')

@section('content')
<div class="max-w-md mx-auto p-6 sm:p-8">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-theme-lg space-y-6">
        
        <!-- Header / Brand -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-500 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                Lupa Kata Sandi?
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Masukkan email Anda untuk menerima link reset kata sandi
            </p>
        </div>

        @if (session('success'))
            <x-alerts.alert type="success" :message="session('success')" />
        @endif

        @if ($errors->any())
            <x-alerts.alert type="error" :message="$errors->first()" />
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

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

            <!-- Submit Button with Icon Group + Text -->
            <x-buttons.primary type="submit" class="w-full">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </x-slot:icon>
                Kirim Link Reset Password
            </x-buttons.primary>
        </form>

        <!-- Back to Login Link -->
        <div class="pt-2 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800">
            <a href="{{ route('login') }}" class="font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400 hover:underline inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Halaman Masuk
            </a>
        </div>
    </div>
</div>
@endsection
