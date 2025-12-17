<?php

namespace App\Http\Controllers;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UpdateUserDTO;
use App\Application\User\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function index()
    {
        $users = $this->userService->getPaginatedUsers(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(StoreUserRequest $request)
    {
        $dto = CreateUserDTO::fromArray($request->validated());
        $this->userService->createUser($dto);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $dto = UpdateUserDTO::fromArray($request->validated());
        $this->userService->updateUser($user->id, $dto);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user->id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}

