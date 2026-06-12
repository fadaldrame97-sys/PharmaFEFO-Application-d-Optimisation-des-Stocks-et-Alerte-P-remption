<?php
// src/Repository/StockBatchRepository.php
namespace PharmaFEFO\Repository;

use Database;
use PDO;

class StockBatchRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Épic 2 : Tri par FEFO exact (Expiration la plus proche en premier)
     */
    public function findAllFEFO(): array {
        $sql = "SELECT sb.*, p.name as product_name, p.code as product_code 
                FROM stock_batches sb
                JOIN products p ON sb.product_id = p.id
                ORDER BY sb.expiration_date ASC"; // Khedamna b expiration_date dyalk hna
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * US 1.1 : Enregistrer un nouveau lot en stock
     */
    public function save(array $data): bool {
        $sql = "INSERT INTO stock_batches (product_id, lot_number, quantity, expiration_date, status) 
                VALUES (:product_id, :lot_number, :quantity, :expiration_date, :status)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'product_id'      => $data['product_id'],
            'lot_number'      => $data['lot_number'],
            'quantity'        => $data['quantity'],
            'expiration_date' => $data['expiration_date'],
            'status'          => $data['status']
        ]);
    }

    /**
     * US 3.1 : Système désigne automatiquement le lot le plus proche de la péremption
     */
    public function getPriorityBatch(int $productId): ?array {
        $sql = "SELECT sb.*, p.name as product_name 
                FROM stock_batches sb
                JOIN products p ON sb.product_id = p.id
                WHERE sb.product_id = :product_id AND sb.quantity > 0 AND sb.status != 'EXPIRED'
                ORDER BY sb.expiration_date ASC 
                LIMIT 1";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['product_id' => $productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }
    
    /**
     * Récupérer tous les produits pour les afficher dans le select du formulaire
     */
    public function getAllProducts(): array {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * US 4.1 : Passer le statut à EXPIRED et mettre la quantité à 0
     */
    public function markAsExpired(int $batchId): bool {
        $sql = "UPDATE stock_batches SET status = 'EXPIRED', quantity = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $batchId]);
    }

    /**
     * US 4.2 : Rapport des pertes (Calculer le total des boîtes périmées)
     */
    public function getTotalPertesBoites(): int {
        $sql = "SELECT SUM(quantity) as total_perdu FROM stock_batches WHERE status = 'EXPIRED' OR expiration_date < CURDATE()";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_perdu'] ? (int)$result['total_perdu'] : 0;
    }
    /**
     * Vérifier l'authentification de l'utilisateur
     */
    public function checkLogin(string $email, string $password): ?array {
        $sql = "SELECT * FROM users WHERE email = :email AND password = :password LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email, 'password' => $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}