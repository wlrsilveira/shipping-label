<?php

namespace App\Http\Controllers;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UpdateUserDTO;
use App\Application\User\DTOs\UserResponseDTO;
use App\Application\User\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private UserService $userService
    ) {
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userService->getPaginatedUsers(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return Inertia::render('Users/Create');
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $dto = CreateUserDTO::fromArray($request->validated());
        $this->userService->createUser($dto);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $domainUser = $this->userService->getUserById($user->id);

        if (!$domainUser) {
            abort(404, 'User not found');
        }

        $userDto = UserResponseDTO::fromDomain($domainUser);

        return Inertia::render('Users/Edit', [
            'user' => $userDto->toArray(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $dto = UpdateUserDTO::fromArray($request->validated());
        $this->userService->updateUser($user->id, $dto);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->userService->deleteUser($user->id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}

