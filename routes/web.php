<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShippingLabelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);

    Route::resource('shipping-labels', ShippingLabelController::class)->except(['update', 'edit']);
    Route::get('/us-states', [ShippingLabelController::class, 'usStates'])->name('us-states.index');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Broadcast::routes(['middleware' => ['web', 'auth']]);
});
