@php
    $user = auth()->user();
@endphp

<div class="relative" x-data="{
    dropdownOpen: false,
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },
    closeDropdown() {
        this.dropdownOpen = false;
    }
}" @click.away="closeDropdown()">
    <!-- User Button -->
    <button
        class="flex items-center text-gray-700 dark:text-gray-400 cursor-pointer"
        @click.prevent="toggleDropdown()"
        type="button"
    >
        <span class="mr-3 overflow-hidden rounded-full h-10 w-10 border border-gray-200 dark:border-gray-700">
            <img src="{{ $user ? $user->avatar_url : 'https://ui-avatars.com/api/?name=User&background=465fff&color=ffffff' }}" alt="{{ $user->name ?? 'User' }}" class="h-full w-full object-cover" />
        </span>

        <div class="text-left mr-1 hidden sm:block">
            <span class="block font-medium text-theme-sm text-gray-800 dark:text-white/90">{{ $user->name ?? 'Pengguna' }}</span>
            <span class="block text-theme-xs text-gray-500 dark:text-gray-400 capitalize">{{ $user ? $user->role->label() : 'User' }}</span>
        </div>

        <!-- Chevron Icon -->
        <svg
            class="w-5 h-5 transition-transform duration-200 text-gray-400"
            :class="{ 'rotate-180': dropdownOpen }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Start -->
    <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-3 flex w-64 flex-col rounded-2xl border border-gray-200 bg-white p-4 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900 z-50"
        style="display: none;"
    >
        <!-- User Info -->
        <div class="pb-3 border-b border-gray-100 dark:border-gray-800">
            <span class="block font-semibold text-gray-800 text-theme-sm dark:text-white/90">{{ $user->name ?? 'Pengguna' }}</span>
            <span class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email ?? 'user@example.com' }}</span>
            <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $user ? $user->role->badgeClass() : '' }}">
                {{ $user ? $user->role->label() : 'User' }}
            </span>
        </div>

        <!-- Menu Items -->
        <ul class="flex flex-col gap-1 py-2 border-b border-gray-100 dark:border-gray-800">
            <li>
                <a
                    href="{{ url('/profile') }}"
                    class="flex items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-white/5 dark:hover:text-white transition"
                    @click="closeDropdown()"
                >
                    <span class="text-gray-400 group-hover:text-brand-500 dark:group-hover:text-brand-400">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z" fill="currentColor" />
                        </svg>
                    </span>
                    Profil Saya
                </a>
            </li>
        </ul>

        <!-- Sign Out -->
        <form method="POST" action="{{ url('/logout') }}" class="mt-2">
            @csrf
            <button
                type="submit"
                class="flex items-center w-full gap-3 px-3 py-2 font-medium text-red-600 dark:text-red-400 rounded-lg group text-theme-sm hover:bg-red-50 dark:hover:bg-red-500/10 transition cursor-pointer"
            >
                <span class="text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </span>
                Keluar (Logout)
            </button>
        </form>
    </div>
    <!-- Dropdown End -->
</div>
