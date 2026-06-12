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
        $statement = $this->pdo->prepare($query);
        $statement->execute(['email' => $email]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findById(int $id): ?User
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM users ORDER BY email ASC";
        $statement = $this->pdo->query($query);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($rows as $row) {
            $users[] = $this->hydrate($row);
        }
        return $users;
    }
}
