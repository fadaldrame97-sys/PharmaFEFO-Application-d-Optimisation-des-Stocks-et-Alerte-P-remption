<?php

declare(strict_types=1);

class DashboardController
{
    private StockBatchRepository $stockBatchRepository;

    public function __construct(StockBatchRepository $stockBatchRepository)
    {
        $this->stockBatchRepository = $stockBatchRepository;
        session_start(); 
    }

    public function index(): void{
        
    }
    


}