<?php

declare(strict_types=1);

class StockController
{
    private StockBatchRepository $stockBatchRepository;
    private ProductRepository $productRepository;

    public function __construct(
        StockBatchRepository $stockBatchRepository,
        ProductRepository $productRepository
    ) {
        $this->stockBatchRepository = $stockBatchRepository;
        $this->productRepository = $productRepository;
        session_start();
    }

     public function dispenseProduct(int $productId, int $quantity = 1): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
               $batch = $this->stockBatchRepository->getNextExpiringBatch($productId);

        if ($batch) {
            $newQuantity = $batch->getQuantity() - 1;

            if ($newQuantity >= 0) {
                $this->stockBatchRepository->updateQuantity($batch->getId(), $newQuantity);
                $_SESSION['success'] = "Dispensation réussie : lot {$batch->getLotNumber()} décrémenté.";
            } else {
                $_SESSION['error'] = "Stock insuffisant pour ce lot.";
            }
        } else {
            $_SESSION['error'] = "Aucun lot disponible pour ce produit.";
        }

        header('Location: /dashboard');
        exit;
    }


    public function markExpired(int $batchId): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $this->stockBatchRepository->markAsExpired($batchId);
        $_SESSION['success'] = "Lot $batchId marqué comme expiré.";
        header('Location: /dashboard');
        exit;
    }

     public function index(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $batches = $this->stockBatchRepository->findAll();
        require __DIR__ . '/../templates/stock/index.php';

    }

    public function index(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }

    $role = $_SESSION['user']['role'];
    $batches = $this->stockBatchRepository->findAll();

    if ($role === 'ADMIN' || $role === 'GESTIONNAIRE') {
        require __DIR__ . '/../templates/stock/index.php'; // Vue avec actions
    } elseif ($role === 'PHARMACIEN') {
        require __DIR__ . '/../templates/stock/read_only.php'; // Vue lecture seule
    } else {
        die("Accès interdit.");
    }
}


    }


    
    

    