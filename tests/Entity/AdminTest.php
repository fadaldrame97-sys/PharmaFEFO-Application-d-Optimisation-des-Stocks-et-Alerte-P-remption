<?php

declare(strict_types=1);

namespace Tests\Entity;

use PHPUnit\Framework\TestCase;
use Admin;
use User;

class AdminTest extends TestCase
{
    private Admin $admin;

    protected function setUp(): void
    {
        $this->admin = new Admin(1, 'admin@pharma.fr', 'hashed_pw', 'ADMIN');
    }

    public function testAdminExtendsUser(): void
    {
        $this->assertInstanceOf(User::class, $this->admin);
    }

    public function testConstructorSetsInheritedProperties(): void
    {
        $this->assertSame(1, $this->admin->getId());
        $this->assertSame('admin@pharma.fr', $this->admin->getEmail());
        $this->assertSame('hashed_pw', $this->admin->getPassword());
        $this->assertSame('ADMIN', $this->admin->getRole());
    }

    public function testInheritedSetters(): void
    {
        $this->admin->setEmail('new@pharma.fr');
        $this->assertSame('new@pharma.fr', $this->admin->getEmail());

        $this->admin->setPassword('new_pw');
        $this->assertSame('new_pw', $this->admin->getPassword());

        $this->admin->setRole('GESTIONNAIRE');
        $this->assertSame('GESTIONNAIRE', $this->admin->getRole());
    }
}
