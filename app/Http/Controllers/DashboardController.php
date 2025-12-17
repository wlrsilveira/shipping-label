<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();

        return Inertia::render('Dashboard', [
            'usersCount' => $usersCount,
        ]);
    }
}

