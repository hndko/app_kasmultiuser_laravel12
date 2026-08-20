<?php

namespace App\Services\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationService
{
    /**
     * Attempt login with email, password, and active status check.
     *
     * @throws ValidationException
     */
    public function login(array $credentials, bool $remember = false, ?Request $request = null): bool
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi administrator.',
            ]);
        }

        Auth::login($user, $remember);

        if ($request) {
            $request->session()->regenerate();
        }

        // Update last login timestamp
        $user->update(['last_login_at' => now()]);

        return true;
    }

    /**
     * Register a new user.
     */
    public function register(array $data, ?Request $request = null): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        if ($request) {
            $request->session()->regenerate();
        }

        return $user;
    }

    /**
     * Logout and invalidate session.
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
