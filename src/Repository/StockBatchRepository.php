<?php

declare(strict_types=1);

class StockBatchRepository extends AbstractRepository
{
    private function hydrate(array $row): StockBatch
    {
        return new StockBatch(
            (int) $row['id'],
            (int) $row['product_id'],
            $row['lot_number'],
            (int) $row['quantity'],
            new DateTime($row['expiration_date']),
            $row['status'] ?: 'AVAILABLE'
        );
    }

    public function create(StockBatch $stockBatch): bool
    {
        $query = "INSERT INTO stock_batches (product_id, lot_number, quantity, expiration_date, status)
                  VALUES (:product_id, :lot_number, :quantity, :expiration_date, :status)";
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'product_id'      => $stockBatch->getProductId(),
            'lot_number'      => $stockBatch->getLotNumber(),
            'quantity'        => $stockBatch->getQuantity(),
            'expiration_date' => $stockBatch->getExpirationDate()->format('Y-m-d'),
            'status'          => $stockBatch->getStatus(),
        ]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM stock_batches ORDER BY expiration_date ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $batches = [];
        foreach ($rows as $row) {
            $batches[] = $this->hydrate($row);
        }
        return $batches;
    }

    public function findById(int $id): ?StockBatch
    {
        $query = "SELECT * FROM stock_batches WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * FEFO: returns the batch with the earliest expiration date
     * that still has stock and is not expired.
     */
    public function getNextExpiringBatch(int $productId): ?StockBatch
    {
        $query = "SELECT * FROM stock_batches
                  WHERE product_id = :product_id
                    AND quantity > 0
                    AND status <> 'EXPIRED'
                  ORDER BY expiration_date ASC
                  LIMIT 1";
        $statement = $this->pdo->prepare($query);
        $statement->execute(['product_id' => $productId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findByStatus(string $status): array
    {
        $query = "SELECT * FROM stock_batches WHERE status = :status ORDER BY expiration_date ASC";
        $statement = $this->pdo->prepare($query);
        $statement->execute(['status' => $status]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $batches = [];
        foreach ($rows as $row) {
            $batches[] = $this->hydrate($row);
        }
        return $batches;
    }

    public function findExpiringNextMonth(): array
    {
        $query = "SELECT * FROM stock_batches
                  WHERE expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                    AND quantity > 0
                    AND status <> 'EXPIRED'
                  ORDER BY expiration_date ASC";
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $batches = [];
        foreach ($rows as $row) {
            $batches[] = $this->hydrate($row);
        }
        return $batches;
    }

    public function findExpiredBatches(): array
    {
        $query = "SELECT * FROM stock_batches
                  WHERE status = 'EXPIRED' OR expiration_date < CURDATE()
                  ORDER BY expiration_date ASC";
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $batches = [];
        foreach ($rows as $row) {
            $batches[] = $this->hydrate($row);
        }
        return $batches;
    }

    public function updateQuantity(int $batchId, int $quantity): bool
    {
        $query = "UPDATE stock_batches SET quantity = :quantity WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        return $statement->execute(['quantity' => $quantity, 'id' => $batchId]);
    }

    public function markAsExpired(int $id): bool
    {
        $query = "UPDATE stock_batches SET status = 'EXPIRED', quantity = 0 WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        return $statement->execute(['id' => $id]);
    }

    public function findByCriticality(): array
    {
        $query = "SELECT *,
                  CASE
                      WHEN expiration_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'CRITICAL'
                      WHEN expiration_date <= DATE_ADD(CURDATE(), INTERVAL 90 DAY) THEN 'WARNING'
                      ELSE 'OK'
                  END AS criticity
                  FROM stock_batches
                  WHERE status <> 'EXPIRED'
                  ORDER BY expiration_date ASC";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByStatus(): array
    {
        $query = "SELECT status, COUNT(*) as total FROM stock_batches GROUP BY status";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
