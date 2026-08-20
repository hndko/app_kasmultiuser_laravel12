@extends('layouts.app-auth')

@section('content')
<div class="max-w-md mx-auto p-6 sm:p-8">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-theme-lg space-y-6">
        
        <!-- Header / Brand -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-500 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                Sistem Kas Sederhana
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Silakan masuk menggunakan akun Anda
            </p>
        </div>

        @if (session('status'))
            <x-alerts.alert type="success" :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-alerts.alert type="error" :message="$errors->first()" />
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
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

            <!-- Password Input with Icon Group & Placeholder -->
            <x-forms.input
                name="password"
                type="password"
                label="Kata Sandi"
                placeholder="••••••••"
                required
            >
                <x-slot:iconSlot>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </x-slot:iconSlot>
            </x-forms.input>

            <!-- Remember & Forgot Password -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer select-none text-gray-600 dark:text-gray-400">
                    <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 dark:border-gray-700 text-brand-500 focus:ring-brand-500/20" />
                    <span>Ingat Saya</span>
                </label>

                <a href="{{ route('password.request') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400 hover:underline">
                    Lupa Password?
                </a>
            </div>

            <!-- Submit Button with Icon Group + Text -->
            <x-buttons.primary type="submit" class="w-full">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </x-slot:icon>
                Masuk ke Aplikasi
            </x-buttons.primary>
        </form>

        <!-- Register Link -->
        <div class="pt-2 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400 hover:underline">
                Daftar Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
