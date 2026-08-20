<?php

namespace App\Services\Modules\Profile;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * Update user profile information and avatar.
     */
    public function updateProfile(User $user, array $data, ?UploadedFile $avatarFile = null): User
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if ($avatarFile) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $avatarFile->store('avatars', 'public');
            $updateData['avatar'] = $path;
        }

        $user->update($updateData);

        return $user;
    }

    /**
     * Change user password.
     */
    public function changePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}
