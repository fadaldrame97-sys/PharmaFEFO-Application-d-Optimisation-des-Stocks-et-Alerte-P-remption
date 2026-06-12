<?php

declare(strict_types=1);

namespace Tests\Repository;

use Product;
use ProductRepository;

class ProductRepositoryTest extends DatabaseTestCase
{
    private ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductRepository();
    }

    protected function seedData(): void
    {
        $this->pdo->exec("INSERT INTO products (id, name, code, description) VALUES (1, 'Paracétamol', 'PARA-001', 'Antalgique 500mg')");
        $this->pdo->exec("INSERT INTO products (id, name, code, description) VALUES (2, 'Ibuprofène', 'IBU-002', 'Anti-inflammatoire 400mg')");
        $this->pdo->exec("INSERT INTO products (id, name, code, description) VALUES (3, 'Amoxicilline', 'AMOX-003', 'Antibiotique 1g')");
    }

    public function testCreateInsertsProduct(): void
    {
        $product = new Product(0, 'Doliprane', 'DOL-004', 'Paracétamol générique');
        $result = $this->repository->create($product);
        $this->assertTrue($result);

        $row = $this->pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch();
        $this->assertSame(4, (int)$row['cnt']);
    }

    public function testCreateSetsCorrectValues(): void
    {
        $product = new Product(0, 'Doliprane', 'DOL-004', 'Paracétamol générique');
        $this->repository->create($product);

        $row = $this->pdo->query("SELECT * FROM products WHERE code = 'DOL-004'")->fetch(\PDO::FETCH_ASSOC);
        $this->assertNotFalse($row);
        $this->assertSame('Doliprane', $row['name']);
        $this->assertSame('DOL-004', $row['code']);
        $this->assertSame('Paracétamol générique', $row['description']);
    }

    public function testUpdateModifiesProduct(): void
    {
        $product = new Product(1, 'Paracétamol Modifié', 'PARA-001', 'Nouveau desc');
        $result = $this->repository->update($product);
        $this->assertTrue($result);

        $row = $this->pdo->query("SELECT * FROM products WHERE id = 1")->fetch(\PDO::FETCH_ASSOC);
        $this->assertSame('Paracétamol Modifié', $row['name']);
        $this->assertSame('Nouveau desc', $row['description']);
    }

    public function testUpdatePreservesUnchangedFields(): void
    {
        $product = new Product(2, 'Ibuprofène', 'IBU-NEW', 'Anti-inflammatoire 400mg');
        $this->repository->update($product);

        $row = $this->pdo->query("SELECT * FROM products WHERE id = 2")->fetch(\PDO::FETCH_ASSOC);
        $this->assertSame('IBU-NEW', $row['code']);
        $this->assertSame('Ibuprofène', $row['name']);
    }

    public function testDeleteRemovesProduct(): void
    {
        $result = $this->repository->delete(3);
        $this->assertTrue($result);

        $row = $this->pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch();
        $this->assertSame(2, (int)$row['cnt']);
    }

    public function testDeleteNonExistentReturnsTrueWithNoEffect(): void
    {
        $result = $this->repository->delete(999);
        $this->assertTrue($result);

        $row = $this->pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch();
        $this->assertSame(3, (int)$row['cnt']);
    }

    public function testFindByIdReturnsNullForNonExistent(): void
    {
        $product = $this->repository->findById(999);
        $this->assertNull($product);
    }

    public function testFindByCodeReturnsNullForNonExistent(): void
    {
        $product = $this->repository->findByCode('NONEXISTENT');
        $this->assertNull($product);
    }

    public function testCreateMultipleProducts(): void
    {
        for ($i = 10; $i < 15; $i++) {
            $product = new Product(0, "Product $i", "CODE-$i", "Desc $i");
            $this->repository->create($product);
        }

        $row = $this->pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch();
        $this->assertSame(8, (int)$row['cnt']);
    }

    public function testDeleteAllProducts(): void
    {
        $this->repository->delete(1);
        $this->repository->delete(2);
        $this->repository->delete(3);

        $row = $this->pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch();
        $this->assertSame(0, (int)$row['cnt']);
    }
}
