<?php

declare(strict_types=1);

class ReportController
{
    private StockBatchRepository $stockBatchRepository;

    public function __construct(StockBatchRepository $stockBatchRepository)
    {
        $this->stockBatchRepository = $stockBatchRepository;
    }

    public function index(): void
    {
        AuthMiddleware::requireRole('ADMIN');

        $byCriticality = $this->stockBatchRepository->findByCriticality();
        $countByStatus = $this->stockBatchRepository->countByStatus();

        require __DIR__ . '/../../templates/dashboard/admin/reports.php';
    }
}
