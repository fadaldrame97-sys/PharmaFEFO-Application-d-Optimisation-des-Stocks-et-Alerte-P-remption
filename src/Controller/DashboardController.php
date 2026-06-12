<?php

declare(strict_types=1);

class DashboardController
{
    private StockBatchRepository $stockBatchRepository;

    public function __construct(StockBatchRepository $stockBatchRepository)
    {
        $this->stockBatchRepository = $stockBatchRepository;
    }

    public function index(): void
    {
        AuthMiddleware::requireRole('ADMIN', 'PHARMACIEN');

        $expiringSoon = $this->stockBatchRepository->findExpiringNextMonth();
        $expired      = $this->stockBatchRepository->findExpiredBatches();
        $allBatches   = $this->stockBatchRepository->findAll();

        require __DIR__ . '/../../templates/dashboard/index.php';
    }
}
