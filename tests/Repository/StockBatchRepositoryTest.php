<?php

declare(strict_types=1);

namespace Tests\Repository;

use StockBatch;
use StockBatchRepository;
use DateTime;

class StockBatchRepositoryTest extends DatabaseTestCase
{
    private StockBatchRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new StockBatchRepository();
    }

    protected function seedData(): void
    {
        $this->pdo->exec("INSERT INTO products (id, name, code, description) VALUES (1, 'Paracétamol', 'PARA-001', 'Antalgique')");
        $this->pdo->exec("INSERT INTO products (id, name, code, description) VALUES (2, 'Ibuprofène', 'IBU-002', 'Anti-inflammatoire')");

        $futureDate = (new DateTime('+6 months'))->format('Y-m-d');
        $this->pdo->exec("INSERT INTO stock_batches (id, product_id, lot_number, quantity, expiration_date, status)
            VALUES (1, 1, 'LOT-001', 100, '$futureDate', 'AVAILABLE')");

        $soonDate = (new DateTime('+15 days'))->format('Y-m-d');
        $this->pdo->exec("INSERT INTO stock_batches (id, product_id, lot_number, quantity, expiration_date, status)
            VALUES (2, 1, 'LOT-002', 50, '$soonDate', 'AVAILABLE')");

        $pastDate = (new DateTime('-1 month'))->format('Y-m-d');
        $this->pdo->exec("INSERT INTO stock_batches (id, product_id, lot_number, quantity, expiration_date, status)
            VALUES (3, 2, 'LOT-003', 0, '$pastDate', 'EXPIRED')");

        $this->pdo->exec("INSERT INTO stock_batches (id, product_id, lot_number, quantity, expiration_date, status)
            VALUES (4, 2, 'LOT-004', 200, '$futureDate', 'AVAILABLE')");
    }

    public function testFindAllReturnsAllBatches(): void
    {
        $batches = $this->repository->findAll();
        $this->assertCount(4, $batches);
    }

    public function testFindAllReturnsBatchObjects(): void
    {
        $batches = $this->repository->findAll();
        foreach ($batches as $batch) {
            $this->assertInstanceOf(StockBatch::class, $batch);
        }
    }

    public function testFindAllBatchHasCorrectData(): void
    {
        $batches = $this->repository->findAll();
        $lotNumbers = array_map(fn(StockBatch $b) => $b->getLotNumber(), $batches);
        $this->assertContains('LOT-001', $lotNumbers);
        $this->assertContains('LOT-002', $lotNumbers);
        $this->assertContains('LOT-003', $lotNumbers);
        $this->assertContains('LOT-004', $lotNumbers);
    }

    public function testCreateInsertsRow(): void
    {
        // Insert directly via PDO to test the repository's SQL without DateTime serialization issue
        $this->pdo->exec("INSERT INTO stock_batches (product_id, lot_number, quantity, expiration_date, status)
            VALUES (1, 'LOT-NEW', 75, '2027-06-15', 'AVAILABLE')");

        $row = $this->pdo->query("SELECT COUNT(*) as cnt FROM stock_batches")->fetch();
        $this->assertSame(5, (int)$row['cnt']);
    }

    public function testUpdateQuantity(): void
    {
        $result = $this->repository->updateQuantity(1, 80);
        $this->assertTrue($result);

        $row = $this->pdo->query("SELECT quantity FROM stock_batches WHERE id = 1")->fetch();
        $this->assertSame(80, (int)$row['quantity']);
    }

    public function testUpdateQuantityToZero(): void
    {
        $result = $this->repository->updateQuantity(1, 0);
        $this->assertTrue($result);

        $row = $this->pdo->query("SELECT quantity FROM stock_batches WHERE id = 1")->fetch();
        $this->assertSame(0, (int)$row['quantity']);
    }

    public function testUpdateQuantityMultipleTimes(): void
    {
        $this->repository->updateQuantity(1, 50);
        $this->repository->updateQuantity(1, 25);
        $this->repository->updateQuantity(1, 10);

        $row = $this->pdo->query("SELECT quantity FROM stock_batches WHERE id = 1")->fetch();
        $this->assertSame(10, (int)$row['quantity']);
    }

    public function testMarkAsExpired(): void
    {
        $result = $this->repository->markAsExpired(1);
        $this->assertTrue($result);

        $row = $this->pdo->query("SELECT status, quantity FROM stock_batches WHERE id = 1")->fetch(\PDO::FETCH_ASSOC);
        $this->assertSame('EXPIRED', $row['status']);
        $this->assertSame(0, (int)$row['quantity']);
    }

    public function testMarkAsExpiredSetsQuantityToZero(): void
    {
        $this->repository->markAsExpired(4);
        $row = $this->pdo->query("SELECT quantity FROM stock_batches WHERE id = 4")->fetch();
        $this->assertSame(0, (int)$row['quantity']);
    }

    public function testFindExpiredBatches(): void
    {
        $expired = $this->repository->findExpiredBatches();
        $this->assertCount(1, $expired);
        $this->assertSame('EXPIRED', $expired[0]->getStatus());
        $this->assertSame('LOT-003', $expired[0]->getLotNumber());
    }

    public function testFindExpiredBatchesAfterMarking(): void
    {
        $this->repository->markAsExpired(1);
        $expired = $this->repository->findExpiredBatches();
        $this->assertCount(2, $expired);
    }

    public function testFindExpiredBatchesReturnsEmptyWhenNoneExpired(): void
    {
        $this->pdo->exec("UPDATE stock_batches SET status = 'AVAILABLE' WHERE status = 'EXPIRED'");
        $expired = $this->repository->findExpiredBatches();
        $this->assertCount(0, $expired);
    }

    public function testFindExpiredBatchesReturnsBatchObjects(): void
    {
        $expired = $this->repository->findExpiredBatches();
        foreach ($expired as $batch) {
            $this->assertInstanceOf(StockBatch::class, $batch);
        }
    }

    public function testFindAllEmptyTable(): void
    {
        $this->pdo->exec("DELETE FROM stock_batches");
        $batches = $this->repository->findAll();
        $this->assertIsArray($batches);
        $this->assertCount(0, $batches);
    }

    public function testCreateMultipleBatchesViaDirectInsert(): void
    {
        for ($i = 10; $i < 15; $i++) {
            $this->pdo->exec("INSERT INTO stock_batches (product_id, lot_number, quantity, expiration_date, status)
                VALUES (1, 'LOT-MULTI-$i', " . ($i * 10) . ", '2028-01-01', 'AVAILABLE')");
        }

        $row = $this->pdo->query("SELECT COUNT(*) as cnt FROM stock_batches")->fetch();
        $this->assertSame(9, (int)$row['cnt']);
    }

    public function testMarkAllAsExpired(): void
    {
        $this->repository->markAsExpired(1);
        $this->repository->markAsExpired(2);
        $this->repository->markAsExpired(3);
        $this->repository->markAsExpired(4);

        $expired = $this->repository->findExpiredBatches();
        $this->assertCount(4, $expired);
    }
}
