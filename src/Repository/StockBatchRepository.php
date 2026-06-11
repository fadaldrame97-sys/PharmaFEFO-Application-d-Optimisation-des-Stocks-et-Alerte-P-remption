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
        $statement=$this->pdo->prepare($query);
        return $statement->execute(['product_id'=>$stockBatch->getProductId(),
                                    'lot_number'=>$stockBatch->getLotNumber(),
                                    'quantity'=>$stockBatch->getQuantity(),
                                    'expriration_date'=>$stockBatch->getExpirationDate(), 
                                    'status'=>$stockBatch->getStatus()]);                   
        
    }


    public function findById(int $id): ?StockBatch{
        $query=" SELECT * FROM products WHERE id=:id";
        $statement=$this->pdo->prepare($query);

        if(!$statement)return null;

        $statement->execute(['id'=>$id]);
       
        $batch = $statement->fetchObject(StockBatch::class);

        return $batch ?: null; 

    }
    public function getNextExpiringBatch(int $productId){}


}