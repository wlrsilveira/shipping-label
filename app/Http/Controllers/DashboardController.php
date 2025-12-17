<?php

namespace App\Http\Controllers;

use App\Application\ShippingLabel\Services\ShippingLabelService;
use App\Application\User\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        private UserService $userService,
        private ShippingLabelService $shippingLabelService
    ) {
    }

    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $shippingLabelStats = $this->shippingLabelService->getUserStatsByStatus($userId);
        $usersCount = $this->userService->getTotalUsersCount();

        return Inertia::render('Dashboard', [
            'usersCount' => $usersCount,
            'shippingLabelStats' => $shippingLabelStats,
        ]);
    }
}

