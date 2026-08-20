<?php

namespace App\Http\Controllers\Modules\User;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\User\StoreUserRequest;
use App\Http\Requests\Modules\User\UpdateUserRequest;
use App\Models\User;
use App\Services\Modules\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $filters = $request->only('search', 'role', 'status');
        $users = $this->userService->getPaginatedUsers($filters, 10);

        return view('modules.users.index', [
            'users' => $users,
            'roles' => UserRole::cases(),
            'statuses' => UserStatus::cases(),
            'filters' => $filters,
            'title' => 'Manajemen Pengguna',
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('modules.users.create', [
            'roles' => UserRole::cases(),
            'statuses' => UserStatus::cases(),
            'title' => 'Tambah Pengguna Baru',
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->createUser(
            $request->validated(),
            $request->file('avatar')
        );

        return redirect()->route('modules.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('modules.users.edit', [
            'user' => $user,
            'roles' => UserRole::cases(),
            'statuses' => UserStatus::cases(),
            'title' => 'Edit Pengguna: ' . $user->name,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->userService->updateUser(
                $user,
                $request->validated(),
                $request->file('avatar'),
                auth()->id()
            );

            return redirect()->route('modules.users.index')
                ->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->userService->deleteUser($user, auth()->id());

            return redirect()->route('modules.users.index')
                ->with('success', 'Pengguna berhasil dihapus dari sistem.');
        } catch (ValidationException $e) {
            return redirect()->route('modules.users.index')
                ->with('error', $e->getMessage());
        }
    }
}
