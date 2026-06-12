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
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $role = $_SESSION['user']['role'];
        if ($role !== 'ADMIN' && $role !== 'PHARMACIEN') {
            $_SESSION['error'] = "Acces interdit a cette vue.";
            header('Location: index.php?action=login');
            exit;
        }

        try {
            $expiringSoon = $this->stockBatchRepository->findExpiringNextMonth();
            $expired      = $this->stockBatchRepository->findExpiredBatches();
            $allBatches   = $this->stockBatchRepository->findAll();
        } catch (RuntimeException $e) {
            error_log('DashboardController::index error: ' . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement du tableau de bord.";
            $expiringSoon = [];
            $expired = [];
            $allBatches = [];
        }

        require __DIR__ . '/../../templates/dashboard/index.php';
    }
}
