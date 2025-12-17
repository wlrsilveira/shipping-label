<?php

namespace App\Http\Controllers;

use App\Domain\ShippingLabel\ValueObjects\ShippingLabelStatus;
use App\Models\ShippingLabel;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $userId = auth()->id();

        $countsByStatus = ShippingLabel::where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $shippingLabelStats = [];
        foreach (ShippingLabelStatus::cases() as $status) {
            $shippingLabelStats[$status->value] = [
                'count' => $countsByStatus[$status->value] ?? 0,
                'label' => $status->getLabel(),
            ];
        }

        return Inertia::render('Dashboard', [
            'usersCount' => $usersCount,
            'shippingLabelStats' => $shippingLabelStats,
        ]);
    }
}

