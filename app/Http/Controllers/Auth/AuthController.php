<?php

namespace App\Http\Controllers\Auth;

use App\Application\Auth\DTOs\LoginDTO;
use App\Application\Auth\DTOs\RegisterDTO;
use App\Application\Auth\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        $dto = LoginDTO::fromArray($request->validated());
        $this->authService->login($dto);

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(RegisterRequest $request)
    {
        $dto = RegisterDTO::fromArray($request->validated());
        $this->authService->register($dto);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

