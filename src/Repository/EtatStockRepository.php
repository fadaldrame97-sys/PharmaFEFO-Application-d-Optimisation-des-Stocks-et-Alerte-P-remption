<?php

declare(strict_types=1);

class EtatStockRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    private function hydrate(array $row): EtatStock
    {
        return new EtatStock(
            (int) $row['id'],
            (int) $row['batch_id'],
            $row['status'],
            new DateTime($row['checked_at'])
        );
    }

    public function findByBatchId(int $batchId): array
    {
        $query = "SELECT * FROM etat_stock WHERE batch_id = :batch_id ORDER BY checked_at DESC";
        $statement = $this->pdo->prepare($query);
        $statement->execute(['batch_id' => $batchId]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $etats = [];
        foreach ($rows as $row) {
            $etats[] = $this->hydrate($row);
        }
        return $etats;
    }

    public function create(EtatStock $etat): bool
    {
        $query = "INSERT INTO etat_stock (batch_id, status, checked_at)
                  VALUES (:batch_id, :status, :checked_at)";
        $statement = $this->pdo->prepare($query);
        return $statement->execute([
            'batch_id'   => $etat->getBatchId(),
            'status'     => $etat->getStatus(),
            'checked_at' => $etat->getCheckedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
