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
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            $_SESSION['error'] = "Acces reserve a l'administrateur.";
            header('Location: index.php?action=login');
            exit;
        }

        $byCriticality = $this->stockBatchRepository->findByCriticality();
        $countByStatus = $this->stockBatchRepository->countByStatus();

        require __DIR__ . '/../../templates/dashboard/admin/reports.php';
    }
}
