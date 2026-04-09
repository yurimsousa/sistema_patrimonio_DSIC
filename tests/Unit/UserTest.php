<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_is_admin_retorna_true_para_admin(): void
    {
        $user = new User(['role' => 'admin']);

        $this->assertTrue($user->isAdmin());
    }

    public function test_is_admin_retorna_false_para_outros_roles(): void
    {
        $this->assertFalse((new User(['role' => 'auditor']))->isAdmin());
        $this->assertFalse((new User(['role' => 'usuario']))->isAdmin());
    }

    public function test_is_auditor_retorna_true_para_admin_e_auditor(): void
    {
        $this->assertTrue((new User(['role' => 'admin']))->isAuditor());
        $this->assertTrue((new User(['role' => 'auditor']))->isAuditor());
    }

    public function test_is_auditor_retorna_false_para_usuario_comum(): void
    {
        $user = new User(['role' => 'usuario']);

        $this->assertFalse($user->isAuditor());
    }

    public function test_role_label_retorna_administrador(): void
    {
        $user = new User(['role' => 'admin']);

        $this->assertEquals('Administrador', $user->role_label);
    }

    public function test_role_label_retorna_auditor(): void
    {
        $user = new User(['role' => 'auditor']);

        $this->assertEquals('Auditor', $user->role_label);
    }

    public function test_role_label_retorna_usuario_para_role_padrao(): void
    {
        $user = new User(['role' => 'usuario']);

        $this->assertEquals('Usuário', $user->role_label);
    }

    public function test_role_color_admin_retorna_danger(): void
    {
        $user = new User(['role' => 'admin']);

        $this->assertEquals('danger', $user->role_color);
    }

    public function test_role_color_auditor_retorna_warning(): void
    {
        $user = new User(['role' => 'auditor']);

        $this->assertEquals('warning', $user->role_color);
    }

    public function test_password_nao_aparece_na_serializacao(): void
    {
        $user = new User(['role' => 'admin', 'password' => 'secreta123']);

        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->assertArrayNotHasKey('remember_token', $user->toArray());
    }
}
