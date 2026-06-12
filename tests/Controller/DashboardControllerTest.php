<?php

declare(strict_types=1);

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use DashboardController;
use StockBatchRepository;

class DashboardControllerTest extends TestCase
{
    private MockObject $stockBatchRepository;

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];

        $this->stockBatchRepository = $this->createMock(StockBatchRepository::class);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testConstructorAcceptsRepository(): void
    {
        $controller = new DashboardController($this->stockBatchRepository);
        $this->assertInstanceOf(DashboardController::class, $controller);
    }

    public function testRepositoryMockReturnsExpectedData(): void
    {
        $this->stockBatchRepository->method('findExpiringNextMonth')
            ->willReturn([]);

        $this->stockBatchRepository->method('findExpiredBatches')
            ->willReturn([]);

        $this->stockBatchRepository->method('findAll')
            ->willReturn([]);

        $this->assertSame([], $this->stockBatchRepository->findExpiringNextMonth());
        $this->assertSame([], $this->stockBatchRepository->findExpiredBatches());
        $this->assertSame([], $this->stockBatchRepository->findAll());
    }
}
