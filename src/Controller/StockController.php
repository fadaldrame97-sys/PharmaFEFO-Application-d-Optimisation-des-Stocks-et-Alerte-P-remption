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

     public function dispenseProduct(int $productId): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
               $batch = $this->stockBatchRepository->findBatchByFEFO($productId);

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

    }


    
    

    