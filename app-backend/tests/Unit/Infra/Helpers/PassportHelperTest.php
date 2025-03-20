<?php

use PHPUnit\Framework\TestCase;

class PassportHelperTest extends TestCase
{

    protected function setUp(): void
    {
        require __DIR__ .'/../../../../app/Infra/Helpers/PassportHelper.php';

        parent::setUp();
    }

    public function test_guard_admin_is_true(): void
    {
        $this->assertTrue(true, isGuardAdmin('Administrador'));
    }

    public function test_guard_admin_is_false(): void
    {
        $this->assertFalse(isGuardAdmin('Gerente'));
    }
}
