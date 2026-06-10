<?php

class UserRepository {
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo=Database::getConnection();
    }

    public function findByEmail(string $email): ?User {
        $query="SELECT * FROM Users WERE email=:email";
        $statement=$this->pdo->prepare($query);
        $statement->execute(['email'=>$email]);
        $user=$statement->fetchObject(User::class);
        return $user;

    }
    public function findById(int $id): ?User {

    }

}