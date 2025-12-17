<?php

namespace Tests\Unit\Policies;

use App\Models\ShippingLabel;
use App\Models\User;
use App\Policies\ShippingLabelPolicy;
use PHPUnit\Framework\TestCase;

class ShippingLabelPolicyTest extends TestCase
{
    private ShippingLabelPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new ShippingLabelPolicy();
    }

    private function createUser(int $id): User
    {
        $user = new User();
        $user->id = $id;
        $user->email = "user{$id}@example.com";
        $user->name = "User {$id}";

        return $user;
    }

    private function createShippingLabel(int $id, int $userId): ShippingLabel
    {
        $label = new ShippingLabel();
        $label->id = $id;
        $label->user_id = $userId;

        return $label;
    }

    public function test_any_user_can_view_all_labels(): void
    {
        $user = $this->createUser(1);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_user_can_view_own_label(): void
    {
        $user = $this->createUser(1);
        $label = $this->createShippingLabel(1, 1);

        $this->assertTrue($this->policy->view($user, $label));
    }

    public function test_user_cannot_view_another_users_label(): void
    {
        $user = $this->createUser(1);
        $label = $this->createShippingLabel(1, 2);

        $this->assertFalse($this->policy->view($user, $label));
    }

    public function test_any_user_can_create_labels(): void
    {
        $user = $this->createUser(1);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_user_can_delete_own_label(): void
    {
        $user = $this->createUser(1);
        $label = $this->createShippingLabel(1, 1);

        $this->assertTrue($this->policy->delete($user, $label));
    }

    public function test_user_cannot_delete_another_users_label(): void
    {
        $user = $this->createUser(1);
        $label = $this->createShippingLabel(1, 2);

        $this->assertFalse($this->policy->delete($user, $label));
    }
}
