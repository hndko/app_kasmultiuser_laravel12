<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService
    ) {}

    /**
     * Show the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register', [
            'title' => 'Daftar Akun Baru',
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->authService->register(
            $request->validated(),
            $request
        );

        return redirect()->route('modules.dashboard')
            ->with('success', 'Akun berhasil didaftarkan. Selamat datang, ' . $user->name . '!');
    }
}
