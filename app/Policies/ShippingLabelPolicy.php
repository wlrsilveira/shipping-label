<?php

namespace App\Policies;

use App\Models\ShippingLabel;
use App\Models\User;

class ShippingLabelPolicy
{
    /**
     * Determine if the user can view any shipping labels.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the shipping label.
     */
    public function view(User $user, ShippingLabel $shippingLabel): bool
    {
        return $user->id === $shippingLabel->user_id;
    }

    /**
     * Determine if the user can create shipping labels.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can delete the shipping label.
     */
    public function delete(User $user, ShippingLabel $shippingLabel): bool
    {
        return $user->id === $shippingLabel->user_id;
    }
}
