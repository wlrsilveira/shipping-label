<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use PHPUnit\Framework\TestCase;

class UserPolicyTest extends TestCase
{
    private UserPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new UserPolicy();
    }

    private function createUser(int $id): User
    {
        $user = new User();
        $user->id = $id;
        $user->email = "user{$id}@example.com";
        $user->name = "User {$id}";

        return $user;
    }

    public function test_any_user_can_view_all_users(): void
    {
        $user = $this->createUser(1);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_user_can_view_own_profile(): void
    {
        $user = $this->createUser(1);
        $model = $this->createUser(1);

        $this->assertTrue($this->policy->view($user, $model));
    }

    public function test_user_cannot_view_another_users_profile(): void
    {
        $user = $this->createUser(1);
        $otherUser = $this->createUser(2);

        $this->assertFalse($this->policy->view($user, $otherUser));
    }

    public function test_any_user_can_create_users(): void
    {
        $user = $this->createUser(1);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_user_can_update_own_profile(): void
    {
        $user = $this->createUser(1);
        $model = $this->createUser(1);

        $this->assertTrue($this->policy->update($user, $model));
    }

    public function test_user_cannot_update_another_users_profile(): void
    {
        $user = $this->createUser(1);
        $otherUser = $this->createUser(2);

        $this->assertFalse($this->policy->update($user, $otherUser));
    }

    public function test_user_can_delete_other_users(): void
    {
        $user = $this->createUser(1);
        $otherUser = $this->createUser(2);

        $this->assertTrue($this->policy->delete($user, $otherUser));
    }

    public function test_user_cannot_delete_own_profile(): void
    {
        $user = $this->createUser(1);
        $model = $this->createUser(1);

        $this->assertFalse($this->policy->delete($user, $model));
    }
}
