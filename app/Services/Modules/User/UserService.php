<?php

namespace App\Services\Modules\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * Get paginated users with filters.
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = User::query()->withCount('createdTransactions');

        // Search by keyword (name or email)
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data, ?UploadedFile $avatarFile = null): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => $data['status'],
            'email_verified_at' => now(),
        ];

        if ($avatarFile) {
            $userData['avatar'] = $avatarFile->store('avatars', 'public');
        }

        return User::create($userData);
    }

    /**
     * Update an existing user.
     *
     * @throws ValidationException
     */
    public function updateUser(User $user, array $data, ?UploadedFile $avatarFile = null, int $currentAdminId = 0): User
    {
        // Prevent admin from demoting themselves to a regular user if they are the current logged-in user
        if ($user->id === $currentAdminId && $user->isAdmin() && $data['role'] !== 'admin' && $data['role'] !== \App\Enums\UserRole::ADMIN->value) {
            throw ValidationException::withMessages([
                'role' => 'Anda tidak dapat mengubah role akun Anda sendiri menjadi non-admin.',
            ]);
        }

        // Prevent admin from deactivating their own account
        if ($user->id === $currentAdminId && ($data['status'] === 'inactive' || $data['status'] === \App\Enums\UserStatus::INACTIVE->value)) {
            throw ValidationException::withMessages([
                'status' => 'Anda tidak dapat menonaktifkan akun yang sedang Anda gunakan.',
            ]);
        }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'status' => $data['status'],
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        if ($avatarFile) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = $avatarFile->store('avatars', 'public');
        }

        $user->update($userData);

        return $user;
    }

    /**
     * Delete user with self-deletion protection.
     *
     * @throws ValidationException
     */
    public function deleteUser(User $user, int $currentAdminId): bool
    {
        if ($user->id === $currentAdminId) {
            throw ValidationException::withMessages([
                'user' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ]);
        }

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        return (bool) $user->delete();
    }
}
