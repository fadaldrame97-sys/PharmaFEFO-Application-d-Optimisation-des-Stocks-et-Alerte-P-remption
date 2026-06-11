<?php
class StockBatchRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(StockBatch $stockBatch):bool{
        $query=" INSERT INTO stock_batches( product_id, lot_number,quantity,expiration_date,status)
                 VALUES (:product_id, :lot_number,:quantity,:expiration_date,:status)";
        
    }
}