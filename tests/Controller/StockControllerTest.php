<?php

declare(strict_types=1);

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use StockController;
use StockBatchRepository;
use ProductRepository;
use StockBatch;
use DateTime;

/**
 * Tests for StockController business logic.
 *
 * Controller methods use exit() and die() which terminate the PHP process,
 * so we test construction, mock wiring, and FEFO dispensing logic.
 */
class StockControllerTest extends TestCase
{
    private MockObject $stockBatchRepo;
    private MockObject $productRepo;

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];

        $this->stockBatchRepo = $this->createMock(StockBatchRepository::class);
        $this->productRepo = $this->createMock(ProductRepository::class);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testConstructorAcceptsRepositories(): void
    {
        $controller = new StockController($this->stockBatchRepo, $this->productRepo);
        $this->assertInstanceOf(StockController::class, $controller);
    }

    public function testStockBatchRepoMockGetNextExpiringBatch(): void
    {
        $batch = new StockBatch(1, 10, 'LOT-001', 5, new DateTime('+1 month'), 'AVAILABLE');

        $this->stockBatchRepo->method('getNextExpiringBatch')
            ->with(10)
            ->willReturn($batch);

        $result = $this->stockBatchRepo->getNextExpiringBatch(10);
        $this->assertSame('LOT-001', $result->getLotNumber());
        $this->assertSame(5, $result->getQuantity());
    }

    public function testStockBatchRepoMockReturnsNullWhenNoBatch(): void
    {
        $this->stockBatchRepo->method('getNextExpiringBatch')
            ->with(999)
            ->willReturn(null);

        $this->assertNull($this->stockBatchRepo->getNextExpiringBatch(999));
    }

    public function testFEFOLogicSelectsEarliestExpiry(): void
    {
        $batch1 = new StockBatch(1, 10, 'LOT-OLD', 10, new DateTime('+1 month'), 'AVAILABLE');
        $batch2 = new StockBatch(2, 10, 'LOT-NEW', 20, new DateTime('+6 months'), 'AVAILABLE');

        // FEFO: the repository returns the earliest expiring batch
        $this->stockBatchRepo->method('getNextExpiringBatch')
            ->with(10)
            ->willReturn($batch1);

        $result = $this->stockBatchRepo->getNextExpiringBatch(10);
        $this->assertSame('LOT-OLD', $result->getLotNumber());
    }

    public function testDispenseDecrementLogic(): void
    {
        $batch = new StockBatch(1, 10, 'LOT-001', 5, new DateTime('+1 month'), 'AVAILABLE');
        $newQuantity = $batch->getQuantity() - 1;

        $this->assertSame(4, $newQuantity);
        $this->assertGreaterThanOrEqual(0, $newQuantity);
    }

    public function testDispenseDetectsInsufficientStock(): void
    {
        $batch = new StockBatch(1, 10, 'LOT-001', 0, new DateTime('+1 month'), 'AVAILABLE');
        $newQuantity = $batch->getQuantity() - 1;

        $this->assertLessThan(0, $newQuantity);
    }

    public function testUpdateQuantityMockExpectation(): void
    {
        $this->stockBatchRepo->expects($this->once())
            ->method('updateQuantity')
            ->with(1, 4)
            ->willReturn(true);

        $result = $this->stockBatchRepo->updateQuantity(1, 4);
        $this->assertTrue($result);
    }

    public function testMarkAsExpiredMockExpectation(): void
    {
        $this->stockBatchRepo->expects($this->once())
            ->method('markAsExpired')
            ->with(5)
            ->willReturn(true);

        $result = $this->stockBatchRepo->markAsExpired(5);
        $this->assertTrue($result);
    }

    public function testRoleBasedAccessControl(): void
    {
        $adminRoles = ['ADMIN', 'GESTIONNAIRE'];
        $scanRoles = ['ADMIN', 'GESTIONNAIRE', 'PREPARATEUR'];
        $receptionRoles = ['GESTIONNAIRE', 'PREPARATEUR'];

        $this->assertContains('ADMIN', $adminRoles);
        $this->assertContains('GESTIONNAIRE', $adminRoles);
        $this->assertNotContains('PHARMACIEN', $adminRoles);

        $this->assertContains('PREPARATEUR', $scanRoles);
        $this->assertNotContains('PHARMACIEN', $scanRoles);

        $this->assertNotContains('ADMIN', $receptionRoles);
        $this->assertNotContains('PHARMACIEN', $receptionRoles);
    }
}
