<?php

declare(strict_types=1);

class ProductRepository extends AbstractRepository
{
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
        $statement = $this->pdo->prepare($query);
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findByCode(string $code): ?Product
    {
        $query = "SELECT * FROM products WHERE code = :code";
        $statement = $this->pdo->prepare($query);
        $statement->execute(['code' => $code]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM products ORDER BY name ASC";
        $statement = $this->pdo->query($query);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $products = [];
        foreach ($rows as $row) {
            $products[] = $this->hydrate($row);
        }
        return $products;
    }

    public function create(Product $product): bool
    {
        $query = "INSERT INTO products (name, code, description) VALUES (:name, :code, :description)";
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'name'        => $product->getName(),
            'code'        => $product->getCode(),
            'description' => $product->getDescription(),
        ]);
    }

    public function update(Product $product): bool
    {
        $query = "UPDATE products SET name = :name, code = :code, description = :description WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'id'          => $product->getId(),
            'name'        => $product->getName(),
            'code'        => $product->getCode(),
            'description' => $product->getDescription(),
        ]);
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM products WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        return $statement->execute(['id' => $id]);
    }
}
