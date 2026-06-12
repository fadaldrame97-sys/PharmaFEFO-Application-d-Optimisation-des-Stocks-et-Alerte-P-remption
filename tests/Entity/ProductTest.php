<?php

declare(strict_types=1);

namespace Tests\Entity;

use PHPUnit\Framework\TestCase;
use Product;

class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product(1, 'Paracétamol', 'PARA-001', 'Antalgique 500mg');
    }

    public function testConstructorSetsProperties(): void
    {
        $this->assertSame(1, $this->product->getId());
        $this->assertSame('Paracétamol', $this->product->getName());
        $this->assertSame('PARA-001', $this->product->getCode());
        $this->assertSame('Antalgique 500mg', $this->product->getDescription());
    }

    public function testSetName(): void
    {
        $this->product->setName('Ibuprofène');
        $this->assertSame('Ibuprofène', $this->product->getName());
    }

    public function testSetCode(): void
    {
        $this->product->setCode('IBU-002');
        $this->assertSame('IBU-002', $this->product->getCode());
    }

    public function testSetDescription(): void
    {
        $this->product->setDescription('Anti-inflammatoire 400mg');
        $this->assertSame('Anti-inflammatoire 400mg', $this->product->getDescription());
    }

    public function testSetNameDoesNotAffectOtherProperties(): void
    {
        $this->product->setName('Amoxicilline');
        $this->assertSame(1, $this->product->getId());
        $this->assertSame('PARA-001', $this->product->getCode());
        $this->assertSame('Antalgique 500mg', $this->product->getDescription());
    }

    public function testSetCodeDoesNotAffectOtherProperties(): void
    {
        $this->product->setCode('AMOX-003');
        $this->assertSame(1, $this->product->getId());
        $this->assertSame('Paracétamol', $this->product->getName());
        $this->assertSame('Antalgique 500mg', $this->product->getDescription());
    }

    public function testEmptyStringValues(): void
    {
        $product = new Product(0, '', '', '');
        $this->assertSame(0, $product->getId());
        $this->assertSame('', $product->getName());
        $this->assertSame('', $product->getCode());
        $this->assertSame('', $product->getDescription());
    }

    public function testUnicodeValues(): void
    {
        $product = new Product(99, 'Médicament été', 'MED-été', 'Déscription spéciale');
        $this->assertSame('Médicament été', $product->getName());
        $this->assertSame('MED-été', $product->getCode());
        $this->assertSame('Déscription spéciale', $product->getDescription());
    }

    public function testSettersReturnVoid(): void
    {
        $this->assertNull($this->product->setName('Test'));
        $this->assertNull($this->product->setCode('T-001'));
        $this->assertNull($this->product->setDescription('Desc'));
    }
}
