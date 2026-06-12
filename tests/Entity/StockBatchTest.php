<?php

declare(strict_types=1);

namespace Tests\Entity;

use PHPUnit\Framework\TestCase;
use StockBatch;
use DateTime;

class StockBatchTest extends TestCase
{
    private StockBatch $batch;
    private DateTime $expirationDate;

    protected function setUp(): void
    {
        $this->expirationDate = new DateTime('2026-12-31');
        $this->batch = new StockBatch(
            1,
            10,
            'LOT-2026-001',
            100,
            $this->expirationDate,
            'AVAILABLE'
        );
    }

    public function testConstructorSetsAllProperties(): void
    {
        $this->assertSame(1, $this->batch->getId());
        $this->assertSame(10, $this->batch->getProductId());
        $this->assertSame('LOT-2026-001', $this->batch->getLotNumber());
        $this->assertSame(100, $this->batch->getQuantity());
        $this->assertSame($this->expirationDate, $this->batch->getExpirationDate());
        $this->assertSame('AVAILABLE', $this->batch->getStatus());
    }

    public function testGetIdReturnsInteger(): void
    {
        $this->assertIsInt($this->batch->getId());
    }

    public function testGetProductIdReturnsInteger(): void
    {
        $this->assertIsInt($this->batch->getProductId());
    }

    public function testGetQuantityReturnsInteger(): void
    {
        $this->assertIsInt($this->batch->getQuantity());
    }

    public function testGetExpirationDateReturnsDateTime(): void
    {
        $this->assertInstanceOf(DateTime::class, $this->batch->getExpirationDate());
    }

    public function testZeroQuantity(): void
    {
        $batch = new StockBatch(2, 5, 'LOT-EMPTY', 0, new DateTime(), 'EXPIRED');
        $this->assertSame(0, $batch->getQuantity());
    }

    public function testExpiredStatus(): void
    {
        $batch = new StockBatch(3, 5, 'LOT-EXP', 0, new DateTime('2020-01-01'), 'EXPIRED');
        $this->assertSame('EXPIRED', $batch->getStatus());
        $this->assertSame(0, $batch->getQuantity());
    }

    public function testExpirationDateFormat(): void
    {
        $this->assertSame('2026-12-31', $this->batch->getExpirationDate()->format('Y-m-d'));
    }

    public function testDifferentStatuses(): void
    {
        $statuses = ['AVAILABLE', 'EXPIRED', 'WARNING', 'CRITICAL'];
        foreach ($statuses as $status) {
            $batch = new StockBatch(1, 1, 'LOT-1', 10, new DateTime(), $status);
            $this->assertSame($status, $batch->getStatus());
        }
    }

    public function testLargeQuantity(): void
    {
        $batch = new StockBatch(1, 1, 'LOT-BIG', 999999, new DateTime(), 'AVAILABLE');
        $this->assertSame(999999, $batch->getQuantity());
    }
}
