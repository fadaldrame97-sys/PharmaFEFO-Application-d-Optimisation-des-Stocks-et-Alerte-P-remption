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
                                    'expiration_date'=>$stockBatch->getExpirationDate(), 
                                    'status'=>$stockBatch->getStatus()]);                   
        
    }
    public function findAll(): array {
      $query = "SELECT * FROM stock_batches ORDER BY expiration_date ASC";
      $statement = $this->pdo->query($query);
      return $statement->fetchAll(PDO::FETCH_CLASS, StockBatch::class) ?: [];
    }



    public function findById(int $id): ?StockBatch{
        $query=" SELECT * FROM stock_batches WHERE id=:id";
        $statement=$this->pdo->prepare($query);

        if(!$statement)return null;

        $statement->execute(['id'=>$id]);
       
        $batch = $statement->fetchObject(StockBatch::class);

        return $batch ?: null; 

    }
    public function getNextExpiringBatch(int $productId): ?StockBatch {
        $query = "SELECT * FROM stock_batches
              WHERE product_id = :product_id
              AND quantity > 0
              AND status <> 'EXPIRED'
              ORDER BY expiration_date ASC
              LIMIT 1";
        $statement = $this->pdo->prepare($query);
        $statement->execute(['product_id' => $productId]);
        $batch = $statement->fetchObject(StockBatch::class);
        return $batch ?: null;
    }


    public function findByStatus(BatchStatus $status): array{
        $query=" SELECT * FROM stock_batches
        WHERE status=:status";
        $statement=$this->pdo->prepare($query);
        $statement->execute(['status' => $status->value]);
        $line=$statement->fetchAll(PDO::FETCH_CLASS, StockBatch::class);

        return $line;
    
    }

    public function findExpiringNextMonth(): array{
        $query=" SELECT * FROM stock_batches
                WHERE expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                 AND quantity > 0
                 AND status <> 'EXPIRED'
                ORDER BY expiration_date ASC";
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


    public function markAsExpired(int $id): bool{
         $query = "
                 UPDATE stock_batches
                 SET status = 'EXPIRED',
                 quantity = 0
                 WHERE id = :id
                 ";

        $statement = $this->pdo->prepare($query);

        return $statement->execute(['id' => $id ]);



    }

    public function findExpiredBatches(): array
    {
        $query = "
           SELECT *
           FROM stock_batches
           WHERE status = 'EXPIRED'
        ";

        $statement = $this->pdo->prepare($query);

        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $batches = [];

       foreach ($data as $row) {
    $expirationDate = !empty($row['expiration_date'])
        ? new DateTime($row['expiration_date'])
        : new DateTime(); // valeur par défaut si vide

    $batches[] = new StockBatch(
        (int)$row['product_id'],
        $row['lot_number'],
        (int)$row['quantity'],
        $expirationDate,
        $row['status']
    );
}


    return $batches;
    }

    public function findByCriticality(): array {
    $query = "SELECT *, 
              CASE 
                WHEN expiration_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'CRITICAL'
                WHEN expiration_date <= DATE_ADD(CURDATE(), INTERVAL 90 DAY) THEN 'WARNING'
                ELSE 'OK'
              END AS criticity
              FROM stock_batches
              WHERE status <> 'EXPIRED'
              ORDER BY expiration_date ASC";
    $statement = $this->pdo->query($query);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
    }





}
