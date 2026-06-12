<?php

declare(strict_types=1);

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    private function hydrate(array $row): User
    {
        return new User(
            (int) $row['id'],
            $row['email'],
            $row['password'],
            $row['role']
        );
    }

    public function findByEmail(string $email): ?User
    {
        $query = "SELECT * FROM users WHERE email = :email";

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute(['email' => $email]);
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->hydrate($row) : null;
        } catch (PDOException $e) {
            error_log('UserRepository::findByEmail failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to find user by email: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findById(int $id): ?User
    {
        $query = "SELECT * FROM users WHERE id = :id";

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute(['id' => $id]);
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->hydrate($row) : null;
        } catch (PDOException $e) {
            error_log('UserRepository::findById failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to find user by ID: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM users ORDER BY email ASC";

        try {
            $statement = $this->pdo->query($query);
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

            $users = [];
            foreach ($rows as $row) {
                $users[] = $this->hydrate($row);
            }
            return $users;
        } catch (PDOException $e) {
            error_log('UserRepository::findAll failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to retrieve users: ' . $e->getMessage(), 0, $e);
        }
    }
}
