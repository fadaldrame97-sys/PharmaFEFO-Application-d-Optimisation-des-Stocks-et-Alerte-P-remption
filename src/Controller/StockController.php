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


    

    }