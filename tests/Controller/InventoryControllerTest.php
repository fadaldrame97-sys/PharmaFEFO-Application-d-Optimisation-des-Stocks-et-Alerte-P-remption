<?php

declare(strict_types=1);

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use InventoryController;
use StockBatchRepository;

class InventoryControllerTest extends TestCase
{
    private MockObject $stockBatchRepo;

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];

        $this->stockBatchRepo = $this->createMock(StockBatchRepository::class);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testConstructorAcceptsRepository(): void
    {
        $controller = new InventoryController($this->stockBatchRepo);
        $this->assertInstanceOf(InventoryController::class, $controller);
    }

    public function testRepositoryMockReturnsBatches(): void
    {
        $this->stockBatchRepo->method('findAll')
            ->willReturn([]);

        $this->assertSame([], $this->stockBatchRepo->findAll());
    }
}
