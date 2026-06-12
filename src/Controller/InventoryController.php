<?php

declare(strict_types=1);

class InventoryController
{
    private StockBatchRepository $stockBatchRepository;

    public function __construct(StockBatchRepository $stockBatchRepository)
    {
        $this->stockBatchRepository = $stockBatchRepository;
    }

    public function validate(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'PHARMACIEN') {
            $_SESSION['error'] = "Acces non autorise.";
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $batchId = (int) ($_POST['batchId'] ?? 0);
            if ($batchId > 0) {
                $batch = $this->stockBatchRepository->findById($batchId);
                if ($batch) {
                    $_SESSION['success'] = "Lot #$batchId valide avec succes.";
                } else {
                    $_SESSION['error'] = "Lot introuvable.";
                }
            }
            header('Location: index.php?action=inventory');
            exit;
        }

        $batches = $this->stockBatchRepository->findAll();
        require __DIR__ . '/../../templates/inventory/validate.php';
    }
}
