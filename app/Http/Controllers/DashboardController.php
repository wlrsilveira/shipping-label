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

        $shippingLabelStats = [];
        foreach (ShippingLabelStatus::cases() as $status) {
            $shippingLabelStats[$status->value] = [
                'count' => ShippingLabel::where('status', $status->value)->count(),
                'label' => $status->getLabel(),
            ];
        }

        return Inertia::render('Dashboard', [
            'usersCount' => $usersCount,
            'shippingLabelStats' => $shippingLabelStats,
        ]);
    }
}

