<?php

declare(strict_types=1);

namespace Tests\Entity;

use PHPUnit\Framework\TestCase;
use Pharmacien;
use User;

class PharmacienTest extends TestCase
{
    private Pharmacien $pharmacien;

    protected function setUp(): void
    {
        $this->pharmacien = new Pharmacien(2, 'pharma@pharma.fr', 'hashed_pw', 'PHARMACIEN');
    }

    public function testPharmacienExtendsUser(): void
    {
        $this->assertInstanceOf(User::class, $this->pharmacien);
    }

    public function testConstructorSetsInheritedProperties(): void
    {
        $this->assertSame(2, $this->pharmacien->getId());
        $this->assertSame('pharma@pharma.fr', $this->pharmacien->getEmail());
        $this->assertSame('hashed_pw', $this->pharmacien->getPassword());
        $this->assertSame('PHARMACIEN', $this->pharmacien->getRole());
    }

    public function testInheritedSetters(): void
    {
        $this->pharmacien->setEmail('new@pharma.fr');
        $this->assertSame('new@pharma.fr', $this->pharmacien->getEmail());

        $this->pharmacien->setPassword('new_pw');
        $this->assertSame('new_pw', $this->pharmacien->getPassword());

        $this->pharmacien->setRole('ADMIN');
        $this->assertSame('ADMIN', $this->pharmacien->getRole());
    }
}
