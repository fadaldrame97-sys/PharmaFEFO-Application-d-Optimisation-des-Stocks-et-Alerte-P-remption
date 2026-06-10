<?php

class UserRepository {
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo=Database::getConnection();
    }

    public function findByEmail(string $email): ?User {

    }
     public function findById(int $id): ?User {
        
     }

}