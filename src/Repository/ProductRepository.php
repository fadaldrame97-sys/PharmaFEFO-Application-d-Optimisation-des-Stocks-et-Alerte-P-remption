<?php

declare(strict_types=1);

class ProductRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    private function hydrate(array $row): Product
    {
        return new Product(
            (int) $row['id'],
            $row['name'],
            $row['code'],
            $row['description'] ?? ''
        );
    }

    public function findById(int $id): ?Product
    {
        $query = "SELECT * FROM products WHERE id = :id";

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute(['id' => $id]);
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->hydrate($row) : null;
        } catch (PDOException $e) {
            error_log('ProductRepository::findById failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to find product by ID: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findByCode(string $code): ?Product
    {
        $query = "SELECT * FROM products WHERE code = :code";

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute(['code' => $code]);
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->hydrate($row) : null;
        } catch (PDOException $e) {
            error_log('ProductRepository::findByCode failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to find product by code: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM products ORDER BY name ASC";

        try {
            $statement = $this->pdo->query($query);
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

            $products = [];
            foreach ($rows as $row) {
                $products[] = $this->hydrate($row);
            }
            return $products;
        } catch (PDOException $e) {
            error_log('ProductRepository::findAll failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to retrieve products: ' . $e->getMessage(), 0, $e);
        }
    }

    public function create(Product $product): bool
    {
        $query = "INSERT INTO products (name, code, description) VALUES (:name, :code, :description)";

        try {
            $statement = $this->pdo->prepare($query);
            return $statement->execute([
                'name'        => $product->getName(),
                'code'        => $product->getCode(),
                'description' => $product->getDescription(),
            ]);
        } catch (PDOException $e) {
            error_log('ProductRepository::create failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to create product: ' . $e->getMessage(), 0, $e);
        }
    }

    public function update(Product $product): bool
    {
        $query = "UPDATE products SET name = :name, code = :code, description = :description WHERE id = :id";

        try {
            $statement = $this->pdo->prepare($query);
            $result = $statement->execute([
                'id'          => $product->getId(),
                'name'        => $product->getName(),
                'code'        => $product->getCode(),
                'description' => $product->getDescription(),
            ]);

            if ($result && $statement->rowCount() === 0) {
                throw new RuntimeException("No product found with ID {$product->getId()}.");
            }

            return $result;
        } catch (PDOException $e) {
            error_log('ProductRepository::update failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to update product: ' . $e->getMessage(), 0, $e);
        }
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM products WHERE id = :id";

        try {
            $statement = $this->pdo->prepare($query);
            $result = $statement->execute(['id' => $id]);

            if ($result && $statement->rowCount() === 0) {
                throw new RuntimeException("No product found with ID $id to delete.");
            }

            return $result;
        } catch (PDOException $e) {
            error_log('ProductRepository::delete failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to delete product: ' . $e->getMessage(), 0, $e);
        }
    }
}
