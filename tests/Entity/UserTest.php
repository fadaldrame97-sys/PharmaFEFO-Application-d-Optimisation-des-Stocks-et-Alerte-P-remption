<?php

declare(strict_types=1);

namespace Tests\Entity;

use PHPUnit\Framework\TestCase;
use User;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User(1, 'admin@pharma.fr', 'hashed_password_123', 'ADMIN');
    }

    public function testConstructorSetsProperties(): void
    {
        $this->assertSame(1, $this->user->getId());
        $this->assertSame('admin@pharma.fr', $this->user->getEmail());
        $this->assertSame('hashed_password_123', $this->user->getPassword());
        $this->assertSame('ADMIN', $this->user->getRole());
    }

    public function testSetEmail(): void
    {
        $this->user->setEmail('new@pharma.fr');
        $this->assertSame('new@pharma.fr', $this->user->getEmail());
    }

    public function testSetPassword(): void
    {
        $this->user->setPassword('new_hashed_password');
        $this->assertSame('new_hashed_password', $this->user->getPassword());
    }

    public function testSetRole(): void
    {
        $this->user->setRole('PHARMACIEN');
        $this->assertSame('PHARMACIEN', $this->user->getRole());
    }

    public function testSetEmailDoesNotAffectOtherProperties(): void
    {
        $this->user->setEmail('changed@pharma.fr');
        $this->assertSame(1, $this->user->getId());
        $this->assertSame('hashed_password_123', $this->user->getPassword());
        $this->assertSame('ADMIN', $this->user->getRole());
    }

    public function testSetPasswordDoesNotAffectOtherProperties(): void
    {
        $this->user->setPassword('changed_pw');
        $this->assertSame(1, $this->user->getId());
        $this->assertSame('admin@pharma.fr', $this->user->getEmail());
        $this->assertSame('ADMIN', $this->user->getRole());
    }

    public function testSetRoleDoesNotAffectOtherProperties(): void
    {
        $this->user->setRole('PREPARATEUR');
        $this->assertSame(1, $this->user->getId());
        $this->assertSame('admin@pharma.fr', $this->user->getEmail());
        $this->assertSame('hashed_password_123', $this->user->getPassword());
    }

    public function testAllRoles(): void
    {
        $roles = ['ADMIN', 'PHARMACIEN', 'PREPARATEUR', 'GESTIONNAIRE'];
        foreach ($roles as $role) {
            $user = new User(1, 'test@test.fr', 'pw', $role);
            $this->assertSame($role, $user->getRole());
        }
    }

    public function testSettersReturnVoid(): void
    {
        $this->assertNull($this->user->setEmail('a@b.c'));
        $this->assertNull($this->user->setPassword('pw'));
        $this->assertNull($this->user->setRole('ADMIN'));
    }
}
