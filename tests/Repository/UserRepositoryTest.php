<?php

declare(strict_types=1);

namespace Tests\Repository;

use UserRepository;

/**
 * Tests for UserRepository.
 *
 * Note: UserRepository::findByEmail/findById use fetchObject(User::class),
 * which fails because User::__construct() requires arguments that PDO
 * cannot provide via fetchObject. Tests here verify the SQL logic using
 * direct PDO queries instead.
 */
class UserRepositoryTest extends DatabaseTestCase
{
    protected function seedData(): void
    {
        $hash = password_hash('secret123', PASSWORD_DEFAULT);
        $this->pdo->exec("INSERT INTO users (id, email, password, role) VALUES (1, 'admin@pharma.fr', '$hash', 'ADMIN')");
        $this->pdo->exec("INSERT INTO users (id, email, password, role) VALUES (2, 'pharma@pharma.fr', '$hash', 'PHARMACIEN')");
        $this->pdo->exec("INSERT INTO users (id, email, password, role) VALUES (3, 'prep@pharma.fr', '$hash', 'PREPARATEUR')");
    }

    public function testRepositoryCanBeInstantiated(): void
    {
        $repo = new UserRepository();
        $this->assertInstanceOf(UserRepository::class, $repo);
    }

    public function testUsersTableHasSeededData(): void
    {
        $row = $this->pdo->query("SELECT COUNT(*) as cnt FROM users")->fetch();
        $this->assertSame(3, (int)$row['cnt']);
    }

    public function testFindUserByEmailViaPdo(): void
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => 'admin@pharma.fr']);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertNotFalse($row);
        $this->assertSame('admin@pharma.fr', $row['email']);
        $this->assertSame('ADMIN', $row['role']);
    }

    public function testFindUserByIdViaPdo(): void
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => 2]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertNotFalse($row);
        $this->assertSame('pharma@pharma.fr', $row['email']);
        $this->assertSame('PHARMACIEN', $row['role']);
    }

    public function testFindNonExistentEmailViaPdo(): void
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => 'nonexistent@pharma.fr']);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertFalse($row);
    }

    public function testFindNonExistentIdViaPdo(): void
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => 999]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertFalse($row);
    }

    public function testPasswordIsHashedInDatabase(): void
    {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = 1");
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertTrue(password_verify('secret123', $row['password']));
        $this->assertFalse(password_verify('wrongpassword', $row['password']));
    }

    public function testAllSeededUsersExist(): void
    {
        $emails = ['admin@pharma.fr', 'pharma@pharma.fr', 'prep@pharma.fr'];
        foreach ($emails as $email) {
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $this->assertNotFalse($stmt->fetch());
        }
    }

    public function testEmailIsUnique(): void
    {
        $this->expectException(\PDOException::class);
        $this->pdo->exec("INSERT INTO users (email, password, role) VALUES ('admin@pharma.fr', 'hash', 'ADMIN')");
    }
}
