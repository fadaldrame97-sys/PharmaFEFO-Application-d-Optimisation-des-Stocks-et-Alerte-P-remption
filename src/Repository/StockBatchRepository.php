<?php

declare(strict_types=1);

class StockBatchRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

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

        try {
            $statement = $this->pdo->prepare($query);
            return $statement->execute([
                'product_id'      => $stockBatch->getProductId(),
                'lot_number'      => $stockBatch->getLotNumber(),
                'quantity'        => $stockBatch->getQuantity(),
                'expiration_date' => $stockBatch->getExpirationDate()->format('Y-m-d'),
                'status'          => $stockBatch->getStatus(),
            ]);
        } catch (PDOException $e) {
            error_log('StockBatchRepository::create failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to create stock batch: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM stock_batches ORDER BY expiration_date ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $batches = [];
            foreach ($rows as $row) {
                $batches[] = $this->hydrate($row);
            }
            return $batches;
        } catch (PDOException $e) {
            error_log('StockBatchRepository::findAll failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to retrieve stock batches: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findById(int $id): ?StockBatch
    {
        $query = "SELECT * FROM stock_batches WHERE id = :id";

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute(['id' => $id]);
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->hydrate($row) : null;
        } catch (PDOException $e) {
            error_log('StockBatchRepository::findById failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to find stock batch by ID: ' . $e->getMessage(), 0, $e);
        }
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

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute(['product_id' => $productId]);
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->hydrate($row) : null;
        } catch (PDOException $e) {
            error_log('StockBatchRepository::getNextExpiringBatch failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to get next expiring batch: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findByStatus(string $status): array
    {
        $query = "SELECT * FROM stock_batches WHERE status = :status ORDER BY expiration_date ASC";

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute(['status' => $status]);
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

            $batches = [];
            foreach ($rows as $row) {
                $batches[] = $this->hydrate($row);
            }
            return $batches;
        } catch (PDOException $e) {
            error_log('StockBatchRepository::findByStatus failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to find batches by status: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findExpiringNextMonth(): array
    {
        $query = "SELECT * FROM stock_batches
                  WHERE expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                    AND quantity > 0
                    AND status <> 'EXPIRED'
                  ORDER BY expiration_date ASC";

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute();
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

            $batches = [];
            foreach ($rows as $row) {
                $batches[] = $this->hydrate($row);
            }
            return $batches;
        } catch (PDOException $e) {
            error_log('StockBatchRepository::findExpiringNextMonth failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to find expiring batches: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findExpiredBatches(): array
    {
        $query = "SELECT * FROM stock_batches
                  WHERE status = 'EXPIRED' OR expiration_date < CURDATE()
                  ORDER BY expiration_date ASC";

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute();
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

            $batches = [];
            foreach ($rows as $row) {
                $batches[] = $this->hydrate($row);
            }
            return $batches;
        } catch (PDOException $e) {
            error_log('StockBatchRepository::findExpiredBatches failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to find expired batches: ' . $e->getMessage(), 0, $e);
        }
    }

    public function updateQuantity(int $batchId, int $quantity): bool
    {
        $query = "UPDATE stock_batches SET quantity = :quantity WHERE id = :id";

        try {
            $statement = $this->pdo->prepare($query);
            $result = $statement->execute(['quantity' => $quantity, 'id' => $batchId]);

            if ($result && $statement->rowCount() === 0) {
                throw new RuntimeException("No stock batch found with ID $batchId.");
            }

            return $result;
        } catch (PDOException $e) {
            error_log('StockBatchRepository::updateQuantity failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to update batch quantity: ' . $e->getMessage(), 0, $e);
        }
    }

    public function markAsExpired(int $id): bool
    {
        $query = "UPDATE stock_batches SET status = 'EXPIRED', quantity = 0 WHERE id = :id";

        try {
            $statement = $this->pdo->prepare($query);
            $result = $statement->execute(['id' => $id]);

            if ($result && $statement->rowCount() === 0) {
                throw new RuntimeException("No stock batch found with ID $id to mark as expired.");
            }

            return $result;
        } catch (PDOException $e) {
            error_log('StockBatchRepository::markAsExpired failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to mark batch as expired: ' . $e->getMessage(), 0, $e);
        }
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

        try {
            $statement = $this->pdo->query($query);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('StockBatchRepository::findByCriticality failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to find batches by criticality: ' . $e->getMessage(), 0, $e);
        }
    }

    public function countByStatus(): array
    {
        $query = "SELECT status, COUNT(*) as total FROM stock_batches GROUP BY status";

        try {
            $statement = $this->pdo->query($query);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('StockBatchRepository::countByStatus failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to count batches by status: ' . $e->getMessage(), 0, $e);
        }
    }
}
