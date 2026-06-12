<?php
class InventoryController
{
    private StockBatchRepository $stockBatchRepository;

    public function __construct(StockBatchRepository $repo)
    {
        $this->stockBatchRepository = $repo;
        session_start();
    }

    public function validate(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'PHARMACIEN') {
            header('Location: /unauthorized');
            exit;
        }

        $batches = $this->stockBatchRepository->findAll();
        require __DIR__ . '/../templates/inventory/validate.php';
    }
}
