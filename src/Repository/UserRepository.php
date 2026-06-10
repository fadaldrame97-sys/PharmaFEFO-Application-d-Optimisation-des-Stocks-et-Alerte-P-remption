<?php

class UserRepository {
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo=Database::getConnection();
    }

    public function findByEmail(string $email): ?User {
        $query="SELECT * FROM users WHERE email=:email";
        $statement=$this->pdo->prepare($query);
        $statement->execute(['email'=>$email]);
        $user=$statement->fetchObject(User::class);
        return $user;

    }
    public function findById(int $id): ?User {
        $query=" SELECT * FROM users WHERE id=:id";
        $statement=$this->pdo->prepare($query);
        $statement->execute(['id'=>$id]);
        $user=$statement->fetchObject(User::class);
        return $user;

    }

}