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


    public function findByCode(string $code): ?Product{
    $query = "SELECT * FROM products WHERE code = :code";
    $statement = $this->pdo->prepare($query);

    if (!$statement) return null;

    $statement->execute(['code' => $code]);
    $product = $statement->fetchObject(Product::class);

    return $product ?: null;
    }

    public function findAll(): array{

        $query = "SELECT * FROM products ORDER BY name ASC";
        $statement = $this->pdo->query($query);

        $products = $statement->fetchAll(PDO::FETCH_CLASS, Product::class);

        return $products ?: [];
    }

    public function create(Product $product): bool{
        $query = "INSERT INTO products (name, code, description) VALUES (:name, :code, :description)";
        $statement = $this->pdo->prepare($query);
        return $statement->execute(['name'=> $product->getName(),'code'=> $product->getCode(),
                                   'description' => $product->getDescription()]);
        }


    public function update(Product $product): bool{
        $query = "UPDATE products SET name = :name, code = :code, description = :description WHERE id = :id";
        $statement = $this->pdo->prepare($query);

        return $statement->execute([
        'id'          => $product->getId(),
        'name'        => $product->getName(),
        'code'        => $product->getCode(),
        'description' => $product->getDescription()
        ]);
    }








}