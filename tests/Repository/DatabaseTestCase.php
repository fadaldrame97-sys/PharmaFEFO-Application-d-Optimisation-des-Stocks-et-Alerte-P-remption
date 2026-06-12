<?php

declare(strict_types=1);

namespace Tests\Repository;

use PHPUnit\Framework\TestCase;
use PDO;

abstract class DatabaseTestCase extends TestCase
{
    protected PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->createSchema();
        $this->seedData();

        // Override Database::getConnection() via reflection
        $reflection = new \ReflectionClass(\Database::class);
        $prop = $reflection->getProperty('pdo');
        $prop->setAccessible(true);
        $prop->setValue(null, $this->pdo);
    }

    protected function tearDown(): void
    {
        // Reset the Database singleton
        $reflection = new \ReflectionClass(\Database::class);
        $prop = $reflection->getProperty('pdo');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }

    private function createSchema(): void
    {
        $this->pdo->exec("
            CREATE TABLE products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                code TEXT NOT NULL,
                description TEXT DEFAULT ''
            )
        ");

        $this->pdo->exec("
            CREATE TABLE stock_batches (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                product_id INTEGER NOT NULL,
                lot_number TEXT NOT NULL,
                quantity INTEGER NOT NULL DEFAULT 0,
                expiration_date TEXT NOT NULL,
                status TEXT DEFAULT 'AVAILABLE',
                FOREIGN KEY (product_id) REFERENCES products(id)
            )
        ");

        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                role TEXT NOT NULL
            )
        ");
    }

    protected function seedData(): void
    {
        // Override in subclasses to insert test data
    }
}
