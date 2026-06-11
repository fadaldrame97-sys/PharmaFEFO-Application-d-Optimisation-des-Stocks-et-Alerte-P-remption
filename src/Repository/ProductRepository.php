<?php
class ProductRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findById(int $id): ?Product
{
    $query = "SELECT * FROM products WHERE id = :id";
    $statement = $this->pdo->prepare($query);

    if (!$statement) return null;

    $statement->execute(['id' => $id]);
    $product = $statement->fetchObject(Product::class);

    return $product ?: null;
}

}