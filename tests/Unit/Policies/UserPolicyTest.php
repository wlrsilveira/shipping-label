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

    public function test_qualquer_usuario_pode_ver_todos_usuarios(): void
    {
        $user = $this->createUser(1);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_usuario_pode_ver_seu_proprio_perfil(): void
    {
        $user = $this->createUser(1);
        $model = $this->createUser(1);

        $this->assertTrue($this->policy->view($user, $model));
    }

    public function test_usuario_nao_pode_ver_perfil_de_outro_usuario(): void
    {
        $user = $this->createUser(1);
        $otherUser = $this->createUser(2);

        $this->assertFalse($this->policy->view($user, $otherUser));
    }

    public function test_qualquer_usuario_pode_criar_usuarios(): void
    {
        $user = $this->createUser(1);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_usuario_pode_atualizar_seu_proprio_perfil(): void
    {
        $user = $this->createUser(1);
        $model = $this->createUser(1);

        $this->assertTrue($this->policy->update($user, $model));
    }

    public function test_usuario_nao_pode_atualizar_perfil_de_outro_usuario(): void
    {
        $user = $this->createUser(1);
        $otherUser = $this->createUser(2);

        $this->assertFalse($this->policy->update($user, $otherUser));
    }

    public function test_usuario_pode_deletar_outros_usuarios(): void
    {
        $user = $this->createUser(1);
        $otherUser = $this->createUser(2);

        $this->assertTrue($this->policy->delete($user, $otherUser));
    }

    public function test_usuario_nao_pode_deletar_seu_proprio_perfil(): void
    {
        $user = $this->createUser(1);
        $model = $this->createUser(1);

        $this->assertFalse($this->policy->delete($user, $model));
    }
}

