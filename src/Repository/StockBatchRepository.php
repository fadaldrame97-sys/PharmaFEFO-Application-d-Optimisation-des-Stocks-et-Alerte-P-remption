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
    public function getNextExpiringBatch(int $productId){
        $query=" SELECT * FROM stock_batches
                 WHERE product_id=:product
                 AND quantity > 0
                 AND statut <> 'EXPIRED'
                 GROUP BY expiration_date ASC
                 LIMIT 1";
        $statement=$this->pdo->prepare($query);

        if(!$statement) return null;
        return $statement->execute(['product_id'=>$productId]);
        $table=$statement->fetchObject(StockBatch::class);
        return $table ?: null;
    }

    public function findByStatus(BatchStatus $status): array{
        $query=" SELECT * FROM stock_batches
        WHERE status=:status";
        $statement=$this->pdo->prepare($query);
        $statement->execute(['stutus'=>$status]);
        $line=$statement->fetchAll(PDO::FETCH_CLASS, StockBatch::class);

        return $line;
    
    }

    public function findExpiringNextMonth(): array{
        $query=" SELECT * FROM stock_batches
                WHERE expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE, INTERVAL 1 MONTH)
                 AND quantity > 0
                 AND statut <> 'EXPIRED'
                GROUP BY expiration_date ASC";
        $statement=$this->pdo->prepare($query);
        if(!$statement) return [];
        $statement->execute();
        $batches = $statement->fetchAll(PDO::FETCH_CLASS, StockBatch::class);

        return $batches ?: [];
    
        
    }

    public function updateQuantity(int $batchId, int $quantity): bool {
        $query="UPDATE stock_batches
                set quantity=:quantity
                where id=:id ";
        $statement=$this->pdo->prepare($query);
        return $statement->execute(['quantity'=>$quantity,'id'=>$batchId]);        
    }





}
