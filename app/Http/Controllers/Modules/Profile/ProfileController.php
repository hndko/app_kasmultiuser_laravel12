<?php

namespace App\Http\Controllers\Modules\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Profile\ChangePasswordRequest;
use App\Http\Requests\Modules\Profile\UpdateProfileRequest;
use App\Services\Modules\Profile\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        return view('modules.profile.index', [
            'user' => $request->user(),
            'title' => 'Pengaturan Profil',
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile(
            $request->user(),
            $request->validated(),
            $request->file('avatar')
        );

        return redirect()->route('modules.profile.index')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Change user password.
     */
    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $this->profileService->changePassword(
            $request->user(),
            $request->validated('password')
        );

        return redirect()->route('modules.profile.index')
            ->with('success', 'Password Anda berhasil diubah.');
    }
}
