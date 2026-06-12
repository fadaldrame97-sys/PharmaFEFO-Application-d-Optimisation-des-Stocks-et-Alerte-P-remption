<?php

declare(strict_types=1);

namespace Tests\Entity;

use PHPUnit\Framework\TestCase;
use Preparateur;
use User;

class PreparateurTest extends TestCase
{
    private Preparateur $preparateur;

    protected function setUp(): void
    {
        $this->preparateur = new Preparateur(3, 'prep@pharma.fr', 'hashed_pw', 'PREPARATEUR');
    }

    public function testPreparateurExtendsUser(): void
    {
        $this->assertInstanceOf(User::class, $this->preparateur);
    }

    public function testConstructorSetsInheritedProperties(): void
    {
        $this->assertSame(3, $this->preparateur->getId());
        $this->assertSame('prep@pharma.fr', $this->preparateur->getEmail());
        $this->assertSame('hashed_pw', $this->preparateur->getPassword());
        $this->assertSame('PREPARATEUR', $this->preparateur->getRole());
    }

    public function testInheritedSetters(): void
    {
        $this->preparateur->setEmail('new@pharma.fr');
        $this->assertSame('new@pharma.fr', $this->preparateur->getEmail());

        $this->preparateur->setPassword('new_pw');
        $this->assertSame('new_pw', $this->preparateur->getPassword());

        $this->preparateur->setRole('PHARMACIEN');
        $this->assertSame('PHARMACIEN', $this->preparateur->getRole());
    }
}
